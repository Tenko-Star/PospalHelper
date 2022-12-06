<?php

namespace PospalHelper\V1\Push;

use GuzzleHttp\Exception\GuzzleException;
use PospalHelper\Core\BaseClient;
use PospalHelper\Core\Exception\RequestException;

class Client extends BaseClient
{
    /**
     * @var array<\Closure>
     */
    private array $handler = [];

    /**
     * 处理推送的信息
     *
     * @param \Closure $handler 处理函数
     * @return void
     */
    public function handler(\Closure $handler): void
    {
        if (!isset($_SERVER['REQUEST_METHOD']) || strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
            // 请求错误
            $this->error('Method Not Allowed', 405);
        }

        $raw = file_get_contents('php://input');
        $params = json_decode($raw, true);
        $header = getallheaders();
        if (empty($params) || empty($header)) {
            $this->error();
        }

        $signature = $header['data-signature'] ?? '';
        $appId = $params['appId'] ?? '';
        if (empty($signature) || empty($appId)) {
            $this->error('Forbidden', 403);
        }

        $checking = strtoupper(md5($appId . $raw));
        if ($checking !== $signature) {
            $this->error('Forbidden', 403);
        }

        $data = $params['body'] ?? [];
        $type = $params['cmd'] ?? '';
        $config = [
            'pushTime' => $params['timestamp'] ?? time(),
            'bornTime' => $params['bornTimeStamp'] ?? 0,
            'version' => $params['version'] ?? 'unknown',
            'appId' => $appId
        ];

        try {
            $result = $handler($type, $data, $config);
            if (is_null($result)) {
                $this->success();
            }
        } catch (\Throwable $any) {
            $this->error($any->getMessage(), $any->getCode());
        }

        $this->error($result);
    }

    public function setErrorHandler(\Closure $handler): Client
    {
        $this->handler[] = $handler;
        return $this;
    }

    /**
     * 获取门店的推送地址
     *
     * @return string
     * @throws RequestException
     * @throws GuzzleException
     */
    public function getPushUrl(): string
    {
        $uri = '/pospal-api2/openapi/v1/openNotificationOpenApi/queryPushUrl';
        $config = $this->getConfig();

        $req = [
            'appId' => $config['appId']
        ];

        return $this->query($uri, $req)['sendUrl'] ?? '';
    }

    /**
     * 更新门店的推送地址
     *
     * @param string $url
     * @return void
     * @throws GuzzleException
     * @throws RequestException
     */
    public function updatePushUrl(string $url): void
    {
        $uri = '/pospal-api2/openapi/v1/openNotificationOpenApi/updatePushUrl';
        $config = $this->getConfig();

        $req = [
            'appId' => $config['appId'],
            'pushUrl' => $url
        ];

        $response = $this->post($uri, $req);
        if (isset($response['errorCode']) && $response['status'] === 'error') {
            throw new RequestException(isset($response['messages']) ? $response['messages'][0] : '', $response['errorCode'] ?? 0);
        }
    }

    private function error($message = 'Bad Request', $code = 400) {
        http_response_code($code);
        echo $message;

        if (!empty($this->handler)) {
            try {
                foreach ($this->handler as $handler) {
                    @$handler($message, $code);
                }
            } catch (\Throwable $throwable) {
                // do nothing
            }
        }

        exit;
    }

    private function success() {
        http_response_code(200);
        echo 'SUCCESS';
        exit;
    }
}