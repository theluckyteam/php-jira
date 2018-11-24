<?php

use LuckyTeam\Jira\Entity\ReadonlyIssue as Issue;
use LuckyTeam\Jira\Repository\RepositoryDispatcher;
use LuckyTeam\Jira\Provider\IssueLinksProvider;

require_once (dirname(__DIR__) . '/vendor/autoload.php');

$config = require (dirname(__DIR__) . '/config/config.php');

$username = $config['username'];
$password = $config['password'];
$endpoint = $config['endpoint'];

class ExampleController {

    /**
     * @var RepositoryDispatcher
     */
    private $repositoryDispatcher;

    private $outputtedIssues;

    private $enableTypeLinks = [];

    /**
     * @var IssueLinksProvider
     */
    private $finder;

    /**
     * ExampleController constructor
     *
     * @param string $endpoint
     * @param string $username
     * @param string $password
     */
    public function __construct(string $endpoint, string $username, string $password)
    {
        $this->repositoryDispatcher = new RepositoryDispatcher(
            $endpoint, $username, $password
        );
        $this->outputtedIssues = [];
    }

    public function run()
    {
        $root = 'NOAH-1014';
        $rootQuery = [
            'key' => $root,
            'fields' => ['id', 'summary', 'issuelinks'],
        ];
        $rootIssue = $this->repositoryDispatcher->getIssue($rootQuery);

        $this->finder = new IssueLinksProvider($rootIssue);
        $this->finder->setDispatcher($this->repositoryDispatcher)
            ->setIssueQuery([
                'fields' => ['id', 'summary', 'issuelinks', 'project'],
                'expand' => [],
                'startAt' => 0,
                'maxResults' => 1000,
            ])
            ->setMaxDepth(3)
            ->build();

        $this->runOutputIssueTree($rootIssue);
    }

    /**
     * @param Issue $issue
     * @param string $linkName
     * @param int $depth
     */
    public function runOutputIssueTree(Issue $issue, $linkName = null, $depth = 0)
    {
        if (!isset($linkName) || empty($this->enableTypeLinks) || in_array($linkName, $this->enableTypeLinks)) {
            if (!array_key_exists($issue->getKey(), $this->outputtedIssues)) {
                $this->outputtedIssues[$issue->getKey()] = 1;

                if (isset($linkName)) {
                    echo str_repeat('-', $depth + 1) . ' ' . $linkName . ' ' . $issue->getKey() . ' - ' . $issue->getSummary() . PHP_EOL;
                } else {
                    echo str_repeat('-', $depth + 1) . ' ' . $issue->getKey() . ' - ' . $issue->getSummary() . PHP_EOL;
                }

                $issueLinks = $issue->getLinks();
                foreach ($issueLinks as $issueLink) {
                    $linkedIssueKey = null;
                    $linkName = null;
                    if (isset($issueLink['outwardIssue'])) {
                        if (isset($issueLink['outwardIssue']['key'], $issueLink['type']['outward'])) {
                            $linkedIssueKey = $issueLink['outwardIssue']['key'];
                            $linkName = $issueLink['type']['outward'];
                        }
                    } elseif (isset($issueLink['inwardIssue'], $issueLink['type']['inward'])) {
                        if (isset($issueLink['inwardIssue']['key'])) {
                            $linkedIssueKey = $issueLink['inwardIssue']['key'];
                            $linkName = $issueLink['type']['inward'];
                        }
                    }
                    if (isset($linkedIssueKey) && $issue->getKey() !== $linkedIssueKey) {
                        $linkedIssue = $this->finder->getIssueByKey($linkedIssueKey);
                        if (isset($linkedIssue)) {
                            $this->runOutputIssueTree($linkedIssue, $linkName, $depth + 1);
                        }
                    }
                }
            } else {
                if (isset($linkName)) {
                    echo str_repeat('-', $depth + 1) . ' ' . $linkName . ' ' . $issue->getKey() . ' - ' . $issue->getSummary() . PHP_EOL;
                } else {
                    echo str_repeat('-', $depth + 1) . ' ' . $issue->getKey() . ' - ' . $issue->getSummary() . PHP_EOL;
                }
            }
        }
    }

    /**
     * @param array $enableTypeLinks
     */
    public function setEnableTypeLinks(array $enableTypeLinks)
    {
        $this->enableTypeLinks = $enableTypeLinks;
    }

}

$example = new ExampleController($endpoint, $username, $password);
$example->setEnableTypeLinks(['is parent task of']);
$example->run();
