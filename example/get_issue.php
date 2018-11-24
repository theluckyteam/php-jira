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
    'fields' => ['id', 'assignee'],
    // 'expand' => [],
    'key' => 'NOAH-1086',
];

$issue = $repositoryDispatcher->getIssue($query);

var_dump($issue->toArray());
