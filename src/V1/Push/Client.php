<?php

namespace PospalHelper\V1\Push;

use GuzzleHttp\Exception\GuzzleException;
use PospalHelper\Core\BaseClient;
use PospalHelper\Core\Exception\RequestException;

class Client extends BaseClient
{
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
        $appKey = $params['appId'] ?? '';
        if (empty($signature) || empty($appKey)) {
            $this->error('Forbidden', 403);
        }

        $checking = strtoupper(md5($appKey . $raw));
        if ($checking !== $signature) {
            $this->error('Forbidden', 403);
        }

        $data = $params['body'] ?? [];
        $type = $params['cmd'] ?? '';
        $config = [
            'pushTime' => $params['timestamp'] ?? time(),
            'bornTime' => $params['bornTimeStamp'] ?? 0,
            'version' => $params['version'] ?? 'unknown',
            'appId' => $appKey
        ];

        $result = $handler($type, $data, $config);
        if (is_null($result)) {
           $this->success();
        }

        $this->error($result);
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

        $this->query($uri, $req);
    }

    private function error($message = 'Bad Request', $code = 400) {
        http_response_code($code);
        echo $message;
        exit;
    }

    private function success() {
        http_response_code(200);
        echo 'SUCCESS';
        exit;
    }
}