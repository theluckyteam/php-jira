<?php

namespace LuckyTeam\Jira\Repository;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use LuckyTeam\Jira\Entity\ReadonlyIssue as Issue;

/**
 * Class IssueRepository
 * @package LuckyTeam\Jira\Repository
 */
class IssueRepository implements RepositoryInterface
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
     * @param mixed $query
     *
     * @return array An array of issue
     * @throws \Exception
     *
     * @see https://developer.atlassian.com/cloud/jira/platform/rest/#api-api-2-issue-issueIdOrKey-get
     */
    public function one($query)
    {
        $authSession = $this->repositoryDispatcher->getCookieAuthSession();

        $url = null;
        $options = [
            RequestOptions::HEADERS => [
                'Cookie' => $authSession->getName() . '=' . $authSession->getValue(),
            ],
        ];

        // Prepares request options
        if (is_scalar($query)) {
            $url = $this->endpoint . '/rest/api/2/issue/' . $query;
        } elseif (is_array($query)
            && (array_key_exists('id', $query) || array_key_exists('key', $query))
        ) {
            // Prepares key by query
            if (isset($query['id'])) {
                $url = $this->endpoint . '/rest/api/2/issue/' . $query['id'];
                unset($query['id']);
            } elseif (isset($query['key'])) {
                $url = $this->endpoint . '/rest/api/2/issue/' . $query['key'];
                unset($query['key']);
            }

            // Prepares query params
            foreach ($query as $param => $value) {
                if ('fields' === $param) {
                    if (is_array($value)) {
                        $query[$param] = implode(',', $value);
                    }
                } else {
                    unset($query[$param]);
                }
            }

            $options[RequestOptions::QUERY] = $query;
        } else {
            throw new \Exception('Bad params');
        }

        $response = $this->client->request('GET', $url, $options);

        $body = $response->getBody();
        $body->seek(0);
        $contents = $body->getContents();

        $issue = $this->createInstance(json_decode($contents, true));

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

        $decodedContents = json_decode($contents, true);

        $issues = [];
        if (isset($decodedContents['issues']) && is_array($decodedContents['issues'])) {
            foreach ($decodedContents['issues'] as $issue) {
                $issues[] = $this->createInstance($issue);
            }
        }

        return $issues;
    }

    /**
     * @param RepositoryDispatcher $repositoryDispatcher
     */
    public function setRepositoryDispatcher(RepositoryDispatcher $repositoryDispatcher)
    {
        $this->repositoryDispatcher = $repositoryDispatcher;
    }

    public function createInstance($properties)
    {
        return new Issue($properties);
    }
}
