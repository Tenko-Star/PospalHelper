<?php

namespace PospalHelper\Core;

use GuzzleHttp\ClientInterface;
use PospalHelper\Core\Auth\Sign;
use PospalHelper\Core\Exception\RequestException;
use PospalHelper\Core\Exception\UnexpectedTypeException;
use PospalHelper\Core\Http\Response;

abstract class BaseClient
{
    protected ServiceContainer $app;
    protected ClientInterface $http;
    protected Sign $sign;
    protected Config $config;

    protected const SIGN_VERSION_V1 = 1;
    protected const SIGN_VERSION_V2 = 2;

    protected ?string $baseUri = null;
    protected int $version = self::SIGN_VERSION_V1;

    private const DefaultOptions = [
        'curl' => [
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        ],
    ];

    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
        $this->http = $app['http'];
        $this->sign = $app['sign'];
        $this->config = $app['config'];

        $this->baseUri = rtrim($this->config->get('baseUri', ''), '/');
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function request(string $url, string $method, array $options = []): array
    {
        $method = strtoupper($method);

        $options = array_merge(self::DefaultOptions, $options);

        $options = $this->fixJsonIssue($options);

        if (property_exists($this, 'baseUri') && !is_null($this->baseUri)) {
            $options['base_uri'] = $this->baseUri;
        }

        $response = $this->getHttpClient()->request($method, $url, $options);
        $response->getBody()->rewind();

        return Response::buildFromPsrResponse($response)->toArray();
    }

    public function getHttpClient(): ClientInterface
    {
        return $this->http;
    }

    public function setHttpClient(ClientInterface $client)
    {
        $this->http = $client;
    }

    protected function fixJsonIssue(array $options): array
    {
        if (isset($options['json']) && is_array($options['json'])) {
            $options['headers'] = array_merge($options['headers'] ?? [], ['Content-Type' => 'application/json']);

            if (empty($options['json'])) {
                $options['body'] = json_encode($options['json'], JSON_FORCE_OBJECT);
            } else {
                $options['body'] = json_encode($options['json'], JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK);
            }

            unset($options['json']);
        }

        return $options;
    }

    public function get(string $url, array $params, array $options = []): array
    {
        $options['headers'] = array_merge($options['headers'], $this->sign($params));

        $params = http_build_query($params);
        $url = substr($url, 0, strpos($url, '?')) . '?' . $params;

        return $this->request($url, 'GET', $options);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(string $url, array $data, array $options = []): array
    {
        $options['headers'] = array_merge($options['headers'] ?? [], $this->sign($data));

        $options['json'] = $data;
        return $this->request($url, 'POST', $options);
    }

    public function query(string $url, array $data, array $options = []): array
    {
        $response = $this->post($url, $data, $options);
        if (isset($response['errorCode']) && $response['status'] === 'error') {
            throw new RequestException(isset($response['messages']) ? $response['messages'][0] : '', $response['errorCode'] ?? 0);
        }

        return $response['data'];
    }

    protected function sign(array $data): array
    {
        switch ($this->version) {
            case self::SIGN_VERSION_V1:
                $sign = $this->sign->v1($this->config['appKey'], $data);
                break;
            case self::SIGN_VERSION_V2:
                $sign = $this->sign->v2($this->config['appId'], $this->config['appKey'], $data);
                break;
            default:
                throw new UnexpectedTypeException();
        }

        return $sign->toArray();
    }

    public function getBaseUri(): ?string
    {
        return $this->baseUri;
    }

    public function getConfig(): array
    {
        return $this->config->toArray();
    }
}