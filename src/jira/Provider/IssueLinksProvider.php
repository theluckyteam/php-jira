<?php

namespace LuckyTeam\Jira\Provider;

use LuckyTeam\Jira\Entity\ReadonlyIssue as Issue;
use LuckyTeam\Jira\Repository\RepositoryDispatcher;
use LuckyTeam\Jira\Util\IssueLinkHelper;

/**
 * Class IssueLinksProvider
 * @package LuckyTeam\Jira\Provider
 */
class IssueLinksProvider {

    /**
     * @var Issue Root of issue tree
     */
    private $root;

    /**
     * @var RepositoryDispatcher of Jira repositories
     */
    private $dispatcher;

    /**
     * @var array Query of issue
     */
    private $issueQuery = [
        'fields' => ['id', 'summary', 'issuelinks'],
        'expand' => [],
        'startAt' => 0,
        'maxResults' => 1000,
    ];

    /**
     * @var integer Max depth of tree
     */
    private $maxDepth = 1;

    /**
     * @var array An array of issues
     */
    private $issues;

    /**
     * @var array An array of populated issues
     */
    private $populatedIssueKeys;

    /**
     * @var integer Current depth of tree
     */
    private $depth;

    /**
     * Provider constructor
     *
     * @param Issue $root
     */
    public function __construct(Issue $root)
    {
        $this->root = $root;
        $this->depth = 0;
        $this->issues = [];
        $this->populatedIssueKeys = [];
    }

    /**
     * Builds issue tree
     */
    public function build()
    {
        $issue = $this->root;
        $this->issues[$issue->getKey()] = $issue;

        $unreceivedIssues = array_diff_key($this->issues, $this->populatedIssueKeys);
        while (count($unreceivedIssues) > 0 && $this->depth < $this->maxDepth) {
            $unpopulatedIssueKeys = [];
            foreach ($unreceivedIssues as $issue) {
                /** @var Issue $issue */
                $this->populatedIssueKeys[$issue->getKey()] = 1;

                foreach ($issue->getLinks() as $issueLink) {
                    $unpopulatedIssueKey = IssueLinkHelper::getLinkedIssueKey($issueLink);
                    if ($unpopulatedIssueKey) {
                        $unpopulatedIssueKeys[$unpopulatedIssueKey] = $unpopulatedIssueKey;
                    }
                }
            }

            $notProcessedIssueKeys = [];
            foreach ($unpopulatedIssueKeys as $issueKey) {
                if (!array_key_exists($issueKey, $this->issues)) {
                    $notProcessedIssueKeys[] = $issueKey;
                }
            }

            if ($notProcessedIssueKeys) {
                $issues = $this->getIssuesFromRepositoryByKeys($notProcessedIssueKeys);
                foreach ($issues as $issue) {
                    if (!array_key_exists($issue->getKey(), $this->issues)) {
                        $this->issues[$issue->getKey()] = $issue;
                    }
                }
            }

            $unreceivedIssues = array_diff_key($this->issues, $this->populatedIssueKeys);
            $this->depth++;
        }
    }

    /**
     * Returns issues by keys from repository
     *
     * @param string[] $keys Issue keys
     *
     * @return Issue[]
     */
    private function getIssuesFromRepositoryByKeys(array $keys)
    {
        $issues = [];
        if ($keys) {
            $query = $this->issueQuery;
            $query['jql'] = 'key IN (' . implode(',', $keys) . ')';
            $issues = $this->dispatcher->getIssues($query);
        }

        return $issues;
    }

    /**
     * Returns issue by keys from memory
     *
     * @param string $key Issue keys
     *
     * @return Issue
     */
    public function getIssueByKey($key)
    {
        if (array_key_exists($key, $this->issues) && isset($this->issues[$key])) {
            return $this->issues[$key];
        }

        return null;
    }

    /**
     * @param RepositoryDispatcher $dispatcher
     *
     * @return $this
     */
    public function setDispatcher(RepositoryDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    /**
     * Sets max depth of tree
     *
     * @param int $maxDepth Max depth of tree
     *
     * @return $this
     */
    public function setMaxDepth(int $maxDepth)
    {
        $this->maxDepth = $maxDepth;

        return $this;
    }

    /**
     * Sets query of issue
     *
     * @param array $issueQuery Query of issue
     *
     * @return $this
     */
    public function setIssueQuery(array $issueQuery)
    {
        $this->issueQuery = $issueQuery;

        return $this;
    }
}
