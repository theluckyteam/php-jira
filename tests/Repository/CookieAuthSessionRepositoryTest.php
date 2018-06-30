<?php

use GuzzleHttp\Client;
use LuckyTeam\Jira\Repository\CookieAuthSessionRepository;
use PHPUnit\Framework\TestCase;

class CookieAuthSessionRepositoryTest extends TestCase
{
    public function testCreate()
    {
        $client = new Client();

        $endpoint = 'https://path-to-jira.example';
        $username = 'jira-username';
        $password = 'jira-password';

        $repository = new CookieAuthSessionRepository($endpoint, $username, $password);
        $repository->setClient($client);

        $this->assertInstanceOf(CookieAuthSessionRepository::class, $repository);
    }
}
