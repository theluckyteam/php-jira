<?php

namespace LuckyTeam\Jira\Repository;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use LuckyTeam\Jira\Entity\CookieAuthSession;
use LuckyTeam\Jira\Entity\ReadonlyIssue;

/**
 * Class RepositoryDispatcher
 * @package LuckyTeam\Jira\Repository
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
            $authSessionRepository->setClient($this->client);
            $this->repositories['cookie_auth_session'] = $authSessionRepository;
        }

        /** @var CookieAuthSessionRepository $authSessionRepository */
        $authSessionRepository = $this->repositories['cookie_auth_session'];

        return $authSessionRepository->get();
    }

    /**
     * @param $query
     * @return ReadonlyIssue
     * @throws \Exception
     */
    public function getIssue($query)
    {
        $issueRepository = $this->getRepository('issue');

        return $issueRepository->one($query);
    }

    /**
     * @param $query
     * @return ReadonlyIssue[]
     */
    public function getIssues($query)
    {
        $issueRepository = $this->getRepository('issue');

        return $issueRepository->all($query);
    }

    /**
     * @param $name
     * @return IssueRepository
     * @throws \Exception
     */
    public function getRepository($name)
    {
        $repository = null;

        if ('issue' === $name
            || IssueRepository::class === $name) {
            if (!isset($this->repositories['issue'])) {
                $repository = new IssueRepository($this->endpoint);
                $repository->setRepositoryDispatcher($this);
                $this->addRepository($repository);
            } else {
                $repository = $this->repositories['issue'];
            }
        } else {
            throw new \Exception('Unknown repository ' . $name);
        }

        return $repository;
    }

    /**
     * Register the repository in the manager
     *
     * @param RepositoryInterface $repository
     *
     * @throws \Exception
     */
    public function addRepository(RepositoryInterface $repository)
    {
        if ($repository instanceof IssueRepository) {
            $type = 'issue';
        } else {
            throw new \Exception('Unknown repository type.');
        }

        $this->repositories[$type] = $repository;
    }
}
