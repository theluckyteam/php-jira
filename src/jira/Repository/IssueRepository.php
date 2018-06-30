<?php

namespace LuckyTeam\Jira\Repository;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * Class IssueRepository
 * @package App\Component
 */
class IssueRepository
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var RepositoryDispatcher
     */
    private $repositoryDispatcher;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * IssueRepository constructor
     *
     * @param string $endpoint
     */
    public function __construct(string $endpoint)
    {
        $this->endpoint = $endpoint;
        $this->client = new Client([
            RequestOptions::TIMEOUT => 6,
            RequestOptions::CONNECT_TIMEOUT => 3,
        ]);
    }

    /**
     * Returns issue of Jira
     *
     * @param string $key Key of issue
     *
     * @return array An array of issue
     */
    public function one($key)
    {
        $authSession = $this->repositoryDispatcher->getCookieAuthSession();

        $response = $this->client->request('GET', $this->endpoint . '/rest/api/2/issue/' . $key, [
            RequestOptions::HEADERS => [
                'Cookie' => $authSession->getName() . '=' . $authSession->getValue(),
            ],
        ]);

        $body = $response->getBody();
        $body->seek(0);
        $contents = $body->getContents();

        $decoder = new JsonDecode(true);
        $issue = $decoder->decode($contents, JsonEncoder::FORMAT);

        return $issue;
    }

    /**
     * Returns issues of Jira
     *
     * @param array $query
     *
     * @return array An array of issues
     */
    public function all($query)
    {
        $authSession = $this->repositoryDispatcher->getCookieAuthSession();

        $response = $this->client->request('POST', $this->endpoint . '/rest/api/2/search', [
            RequestOptions::HEADERS => [
                'Cookie' => $authSession->getName() . '=' . $authSession->getValue(),
            ],
            RequestOptions::JSON => $query,
        ]);

        $body = $response->getBody();
        $body->seek(0);
        $contents = $body->getContents();

        $decoder = new JsonDecode(true);
        $decodedContents = $decoder->decode($contents, JsonEncoder::FORMAT);

        $issues = isset($decodedContents['issues']) ? $decodedContents['issues'] : [];

        return $issues;
    }

    /**
     * @param RepositoryDispatcher $repositoryDispatcher
     */
    public function setRepositoryDispatcher(RepositoryDispatcher $repositoryDispatcher)
    {
        $this->repositoryDispatcher = $repositoryDispatcher;
    }
}
