<?php

namespace LuckyTeam\Jira\Resource;

use LuckyTeam\Jira\CookieSessionClient;
use Psr\Http\Message\ResponseInterface;

class ApiResource
{
    private CookieSessionClient $client;

    public function __construct(
        CookieSessionClient $client
    )
    {
        $this->client = $client;
    }

    /**
     * Executes GET request and returns response.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function get($uri, array $options = []): ResponseInterface
    {
        return $this->client->get($uri, $options);
    }

    /**
     * Executes POST request and returns response.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function post($uri, array $options = []): ResponseInterface
    {
        return $this->client->post($uri, $options);
    }

    /**
     * Executes PATH request and returns response.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function patch($uri, array $options = []): ResponseInterface
    {
        return $this->client->patch($uri, $options);
    }

    /**
     * Executes PUT request and returns response.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function put($uri, array $options = []): ResponseInterface
    {
        return $this->client->put($uri, $options);
    }

    /**
     * Returns converted from JSON body content.
     *
     * @param ResponseInterface $response
     * @return array
     */
    protected function jsonBodyContent(ResponseInterface $response): array
    {
        return $this->client->jsonBodyContent($response);
    }
}
