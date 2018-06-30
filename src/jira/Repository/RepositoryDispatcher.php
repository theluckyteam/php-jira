<?php

namespace LuckyTeam\Jira\Repository;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

/**
 * Class RepositoryDispatcher
 * @package App\Component
 */
class RepositoryDispatcher
{
    /**
     * @var string An name of user
     */
    private $username;

    /**
     * @var string An password of user
     */
    private $password;

    /**
     * @var string An endpoint of service
     */
    private $endpoint;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var array An array of repositories
     */
    private $repositories = [];

    /**
     * RepositoryDispatcher constructor
     *
     * @param string $endpoint An endpoint of service
     * @param string $username An name of user
     * @param string $password An password of user
     */
    public function __construct(string $endpoint, string $username, string $password)
    {
        $this->endpoint = $endpoint;
        $this->username = $username;
        $this->password = $password;
        $this->client = new Client([
            RequestOptions::TIMEOUT => 6,
            RequestOptions::CONNECT_TIMEOUT => 3,
        ]);
    }

    public function getCookieAuthSession() : CookieAuthSession
    {
        if (!isset($this->repositories['cookie_auth_session'])) {
            $authSessionRepository = new CookieAuthSessionRepository(
                $this->endpoint, $this->username, $this->password
            );
            $this->repositories['cookie_auth_session'] = $authSessionRepository;
        }

        /** @var CookieAuthSessionRepository $authSessionRepository */
        $authSessionRepository = $this->repositories['cookie_auth_session'];

        return $authSessionRepository->get();
    }

    public function getIssue($key)
    {
        if (!isset($this->repositories['issue'])) {
            $issueRepository = new IssueRepository($this->endpoint);
            $issueRepository->setRepositoryDispatcher($this);
            $this->repositories['issue'] = $issueRepository;
        }

        /** @var IssueRepository $issueRepository */
        $issueRepository = $this->repositories['issue'];

        return $issueRepository->one($key);
    }

    public function getIssues($query)
    {
        if (!isset($this->repositories['issue'])) {
            $issueRepository = new IssueRepository($this->endpoint);
            $issueRepository->setRepositoryDispatcher($this);
            $this->repositories['issue'] = $issueRepository;
        }

        /** @var IssueRepository $issueRepository */
        $issueRepository = $this->repositories['issue'];

        return $issueRepository->all($query);
    }
}
