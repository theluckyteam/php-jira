<?php

require_once (dirname(__DIR__) . '/vendor/autoload.php');

$config = require (dirname(__DIR__) . '/config/config.php');

$username = $config['username'];
$password = $config['password'];
$endpoint = $config['endpoint'];

$repositoryDispatcher = new \LuckyTeam\Jira\Repository\RepositoryDispatcher(
    $endpoint, $username, $password
);

$query = [
    'fields' => ['id', 'project'],
    'expand' => [],
    'jql' => 'key = NOAH-1014',
    'startAt' => 0,
    'maxResults' => 1000,
];

$issues = $repositoryDispatcher->getIssues($query);

foreach ($issues as $issue) {
    var_dump($issue->getProject());
}
