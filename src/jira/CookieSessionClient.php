<?php

namespace LuckyTeam\Jira;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\RequestOptions;
use LuckyTeam\Jira\Entity\CookieAuthSession;
use Psr\Http\Message\ResponseInterface;

class CookieSessionClient
{
    use ResourcesTrait;

    private const AUTH_SESSION_PATH = '/rest/auth/1/session';

    /**
     * @var string Username of Jira.
     */
    private string $username;

    /**
     * @var string Password of Jira.
     */
    private string $password;

    /**
     * @var string Hostname of Jira.
     */
    private string $hostname;

    /**
     * @var CookieAuthSession Session with credentials to access Jira.
     */
    private CookieAuthSession $authSession;

    /**
     * @var HttpClient
     */
    private HttpClient $httpClient;

    /**
     * Client constructor.
     */
    public function __construct(
        string $hostname,
        string $username,
        string $password,
    )
    {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->httpClient = new HttpClient([
            'base_uri' => $this->hostname,
            RequestOptions::TIMEOUT => 3.,
            RequestOptions::CONNECT_TIMEOUT => 3.,
            RequestOptions::ALLOW_REDIRECTS => true,
        ]);
    }

    /**
     * Executes GET request and returns response.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($uri, array $options = []): ResponseInterface
    {
        return $this->httpClient->get($uri, $this->prepareOptions(
            $options
        ));
    }

    /**
     * Executes POST request and returns response.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post($uri, array $options = []): ResponseInterface
    {
        return $this->httpClient->post($uri, $this->prepareOptions(
            $options
        ));
    }

    /**
     * Executes PATH request and returns response.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function patch($uri, array $options = []): ResponseInterface
    {
        return $this->httpClient->patch($uri, $this->prepareOptions(
            $options
        ));
    }

    /**
     * Executes PUT request and returns response.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function put($uri, array $options = []): ResponseInterface
    {
        return $this->httpClient->put($uri, $this->prepareOptions(
            $options
        ));
    }

    /**
     * Prepared options before request.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function prepareOptions(array $options = []): array
    {
        $options[RequestOptions::HEADERS] = $this->prepareHeaders(
            $options[RequestOptions::HEADERS] ?? []
        );

        return $options;
    }

    /**
     * Prepared headers before request.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function prepareHeaders(array $headers = []): array
    {
        $session = $this->getCookieAuthSession();
        $headers['Cookie'] = $session->getName() . '=' . $session->getValue();

        return $headers;
    }

    /**
     * Returns if exists cookie auth session or fetch its.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCookieAuthSession(): CookieAuthSession
    {
        if (!isset($this->authSession)) {
            $contents = $this->jsonBodyContent(
                $this->fetchCookieAuthSession()
            );
            $this->authSession = new CookieAuthSession(
                $contents['session']['name'], $contents['session']['value']
            );
        }
        return $this->authSession;
    }

    /**
     * Returns response with cookie session data.
     *
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchCookieAuthSession(): ResponseInterface
    {
        $response = $this->httpClient->post(self::AUTH_SESSION_PATH, [
            RequestOptions::JSON => [
                'username' => $this->username,
                'password' => $this->password,
            ],
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception('Failed request to cookie session.');
        }

        return $response;
    }

    /**
     * Returns converted from JSON body content.
     *
     * @param ResponseInterface $response
     * @return array
     */
    public function jsonBodyContent(ResponseInterface $response): array
    {
        $body = $response->getBody();
        $body->seek(0);
        $contents = $body->getContents();

        return json_decode($contents, true);
    }

    protected function getClient(): CookieSessionClient
    {
        return $this;
    }
}
