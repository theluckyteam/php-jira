<?php

namespace LuckyTeam\Jira\Repository;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use LuckyTeam\Jira\Entity\CookieAuthSession;

/**
 * Class CookieAuthSessionRepository
 * @package App\Component
 */
class CookieAuthSessionRepository
{
    /**
     * @var CookieAuthSession
     */
    private $authSession;

    /**
     * @var string Username of Jira
     */
    private $username;

    /**
     * @var string Password of Jira
     */
    private $password;

    /**
     * @var string Endpoint for obtaining AuthSession
     */
    private $endpoint;

    /**
     * @var string Endpoint for obtaining AuthSession
     */
    private $path = '/rest/auth/1/session';

    /**
     * @var Client
     */
    private $client;

    /**
     * CookieAuthSessionRepository constructor
     *
     * @param string $endpoint Endpoint for obtaining AuthSession
     * @param string $username Username of Jira
     * @param string $password Password of Jira
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

    public function get() : CookieAuthSession
    {
        if (!isset($this->authSession)) {
            $this->authSession = $this->create();
        }

        return $this->authSession;
    }

    /**
     * Creates cookie auth session
     *
     * @return CookieAuthSession
     * @throws \Exception If failed to create object
     */
    private function create(): CookieAuthSession
    {
        $response = $this->request($this->endpoint . $this->path, [
            RequestOptions::JSON => [
                'username' => $this->username,
                'password' => $this->password,
            ],
        ]);

        $body = $response->getBody();
        $body->seek(0);
        $contents = $body->getContents();

        $contents = json_decode($contents, true);

        if (isset($contents['session']['name'], $contents['session']['value'])) {
            return new CookieAuthSession($contents['session']['name'], $contents['session']['value']);
        }

        throw new \Exception('Failed to create ' . CookieAuthSession::class . '.');
    }

    /**
     * Requires a cookie authentication session
     *
     * @param string $url Endpoint for obtaining AuthSession
     * @param array $options Request options
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception If failed request
     */
    private function request($url, array $options)
    {
        $response = $this->client->request('POST', $url, $options);

        $body = $response->getBody();
        if ($response->getStatusCode() != 200
                || !isset($body)) {
            throw new \Exception('Failed request to ' . $this->endpoint . '.');
        }

        return $response;
    }

    /**
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }
}
