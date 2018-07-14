<?php

namespace LuckyTeam\Jira\Provider;

use LuckyTeam\Jira\Entity\ReadonlyIssue as Issue;
use LuckyTeam\Jira\Repository\RepositoryDispatcher;

class IssueLinksAggregator {

    /**
     * @var Issue
     */
    private $root;

    /**
     * @var RepositoryDispatcher
     */
    private $dispatcher;

    private $issueQuery = [
        'fields' => ['id', 'summary', 'issuelinks'],
        'expand' => [],
        'startAt' => 0,
        'maxResults' => 1000,
    ];

    /**
     * @var int
     */
    private $maxDepth = 1;

    private $issues;
    private $populatedIssueKeys;
    private $depth;

    /**
     * Finder constructor
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
                    if (isset($issueLink['outwardIssue'])) {
                        $unpopulatedIssue = $issueLink['outwardIssue'];
                    } elseif (isset($issueLink['inwardIssue'])) {
                        $unpopulatedIssue = $issueLink['inwardIssue'];
                    }
                    if (isset($unpopulatedIssue['key'])) {
                        $unpopulatedIssueKeys[$unpopulatedIssue['key']] = $unpopulatedIssue['key'];
                    }
                }
            }

            $notProcessedIssueKeys = [];
            foreach ($unpopulatedIssueKeys as $issueKey) {
                if (!array_key_exists($issueKey, $this->issues)) {
                    $notProcessedIssueKeys[] = $issueKey;
                }
            }

            $issues = $this->getIssuesFromRepositoryByKeys($notProcessedIssueKeys);
            foreach ($issues as $issue) {
                if (!array_key_exists($issue->getKey(), $this->issues)) {
                    $this->issues[$issue->getKey()] = $issue;
                }
            }

            $unreceivedIssues = array_diff_key($this->issues, $this->populatedIssueKeys);
            $this->depth++;
        }
    }

    private function getIssuesFromRepositoryByKeys(array $keys)
    {
        $query = $this->issueQuery;
        $query['jql'] = 'key IN (' . implode(',', $keys) . ')';

        return $this->dispatcher->getIssues($query);
    }

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
     * @param int $maxDepth
     *
     * @return $this
     */
    public function setMaxDepth(int $maxDepth)
    {
        $this->maxDepth = $maxDepth;

        return $this;
    }

    public function childs($issue = null)
    {
        if (null === $issue) {
            $issue = $this->root;
        }

        $issueLinks = $issue->getLinks();
        foreach ($issueLinks as &$issueLink) {
            if (isset($issueLink['outwardIssue'])) {
                if (isset($issueLink['outwardIssue']['key'], $this->issues[$issueLink['outwardIssue']['key']])) {
                    $issueLink['outwardIssue'] = $this->issues[$issueLink['outwardIssue']['key']];
                }
            } elseif (isset($issueLink['inwardIssue'])) {
                if (isset($issueLink['inwardIssue']['key'], $this->issues[$issueLink['inwardIssue']['key']])) {
                    $issueLink['inwardIssue'] = $this->issues[$issueLink['inwardIssue']['key']];
                }
            }
        }

        return $issueLinks;
    }

    /**
     * @return Issue
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param array $issueQuery
     *
     * @return $this
     */
    public function setIssueQuery(array $issueQuery)
    {
        $this->issueQuery = $issueQuery;

        return $this;
    }
}
