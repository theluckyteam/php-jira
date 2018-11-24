<?php

require_once (dirname(__DIR__) . '/vendor/autoload.php');

$config = require (dirname(__DIR__) . '/config/config.php');

$username = $config['username'];
$password = $config['password'];
$endpoint = $config['endpoint'];

$repository = new \LuckyTeam\Jira\Repository\CookieAuthSessionRepository(
    $endpoint, $username, $password
);
$client = $repository->createDefaultClient();
$repository->setClient($client);

$authSession = $repository->get();

var_dump($authSession);
