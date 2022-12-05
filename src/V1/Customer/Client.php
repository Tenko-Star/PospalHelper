<?php

namespace PospalHelper\V1\Customer;

use DateTimeImmutable;
use PospalHelper\Core\BaseClient;
use PospalHelper\Core\Exception\RequestException;
use PospalHelper\Core\Exception\UnexpectedBodyException;
use PospalHelper\Core\Exception\UnexpectedParamsException;
use PospalHelper\Core\Iterator\IteratorResponse;
use PospalHelper\V1\Customer\Validator\CustomerBPValidator;
use PospalHelper\V1\Customer\Validator\CustomerCategoriesValidator;
use PospalHelper\V1\Customer\Validator\CustomerInfoValidator;
use PospalHelper\V1\Customer\Validator\CustomerPasswordValidator;

/**
 * 会员API
 */
class Client extends BaseClient
{
    /**
     * 通过会员号查询会员
     *
     * @param string $customNumber
     * @return array
     * @throws RequestException
     */
    public function queryByNumber(string $customNumber): array
    {
        $uri = '/pospal-api2/openapi/v1/customerOpenApi/queryByNumber';
        $config = $this->getConfig();

        $req = [
            'appId' => $config['appId'],
            'customerNum' => $customNumber,
            'groupShare' => $config['groupShare'] ?? null
        ];

        return $this->query($uri, $req);
    }

    /**
     * 通过Uid查询会员
     *
     * @param string $customerUid
     * @return array
     * @throws RequestException
     */
    public function queryByUid(string $customerUid): array
    {
        $uri = '/pospal-api2/openapi/v1/customerOpenApi/queryByUid';
        $config = $this->getConfig();

        $req = [
            'appId' => $config['appId'],
            'customerUid' => $customerUid,
            'groupShare' => $config['groupShare'] ?? null
        ];

        return $this->query($uri, $req);
    }

    /**
     * 分页查询全部会员
     *
     * @return CustomerIterator
     */
    public function queryCustomerPages(): CustomerIterator
    {
        $client = $this;

        return new CustomerIterator(
            function (?array $params) use ($client) {
                $url = '/pospal-api2/openapi/v1/customerOpenApi/queryCustomerPages';
                $config = $client->getConfig();
                $req = [
                    'appId' => $config['appId']
                ];
                if (!is_null($params)) {
                    $req['postBackParameter'] = $params;
                }
                $response = $client->query($url, $req);

                return new IteratorResponse(
                    $response['result'],
                    $response['pageSize'],
                    $response['postBackParameter']
                );
            }
        );
    }

    /**
     * 修改会员基本信息
     *
     * @param array $customerInfo
     * @return array
     * @throws RequestException
     * @throws UnexpectedParamsException
     */
    public function updateBaseInfo(array $customerInfo): array
    {
        if (isset($customerInfo['customerUid'])) throw new UnexpectedBodyException('updateBaseInfo must have a customerUid');

        $uri = '/pospal-api2/openapi/v1/customerOpenApi/queryByUid';
        $config = $this->getConfig();
        $validator = new CustomerInfoValidator();

        $result = $validator->check($filtered, $customerInfo);
        if (!$result) {
            throw new UnexpectedParamsException($validator->getError());
        }

        $req = [
            'appId' => $config['appId'],
            'customerInfo' => $filtered,
        ];

        return $this->query($uri, $req);
    }

    /**
     * 修改会员余额积分
     *
     * @param array $data
     * @param bool $validateBalance 是否需要验证金额
     * @param bool $validatePoint 是否需要验证积分
     * @param bool $strict 是否启用$data的严格验证，传true则会强制验证，验证规则见<a href="https://github.com/Tenko-Star/PospalHelper/blob/master/doc/Validator.md">这里</a>
     * @return array
     * @throws RequestException
     * @throws UnexpectedParamsException
     */
    public function updateBalancePointByIncrement(array $data, bool $validateBalance = false, bool $validatePoint = false, bool $strict = false): array
    {
        $uri = '/pospal-api2/openapi/v1/customerOpenApi/updateBalancePointByIncrement';
        $config = $this->getConfig();
        $validator = new CustomerBPValidator();

        $result = $validator->check($filtered, $data);
        if (!$result) {
            throw new UnexpectedParamsException($validator->getError());
        }

        $data = array_merge($filtered, [
            'appId' => $config['appId'],
            'dataChangeTime' => date('Y-m-d H:i:s'),
            'validateBalance' => (int)$validateBalance,
            'validatePoint' => (int)$validatePoint,
        ]);

        return $this->query($uri, $data);
    }

    /**
     * 添加会员
     *
     * @param array $customerInfo
     * @param bool $strict 是否启用$customerInfo严格验证，传true则会强制验证，验证规则见<a href="https://github.com/Tenko-Star/PospalHelper/blob/master/doc/Validator.md">这里</a>
     * @return array
     * @throws RequestException
     * @throws UnexpectedParamsException
     */
    public function add(array $customerInfo, bool $strict = false): array
    {
        $uri = '/pospal-api2/openapi/v1/customerOpenApi/add';
        $config = $this->getConfig();
        $validator = new CustomerInfoValidator();

        if ($strict) {
            $result = $validator
                ->except('customerUid')
                ->add('point', ['decimal'])
                ->add('balance', ['decimal'])
                ->data($customerInfo)
                ->check($filtered);
            if (!$result) {
                throw new UnexpectedParamsException($validator->getError());
            }
        } else {
            $filtered = $customerInfo;
        }

        $data = [
            'appId' => $config['appId'],
            'customerInfo' => $filtered
        ];

        return $this->query($uri, $data);
    }

    /**
     * 查询全部通用金额充值记录
     *
     * @param DateTimeImmutable $start
     * @param DateTimeImmutable $end
     * @return CustomerIterator
     * @throws UnexpectedParamsException
     */
    public function queryAllRechargeLogs(DateTimeImmutable $start, DateTimeImmutable $end): CustomerIterator
    {
        $diff = $start->diff($end);
        if ($diff->days > 31) {
            throw new UnexpectedParamsException('The start time interval must be less than 31 days.');
        }

        $start = $start->format('Y-m-d') . ' 00:00:00';
        $end = $end->format('Y-m-d') . ' 00:00:00';

        $client = $this;

        return new CustomerIterator(
            function (?array $params) use ($client, $start, $end) {
                $url = '/pospal-api2/openapi/v1/customerOpenApi/queryAllRechargeLogs';
                $config = $client->getConfig();
                $req = [
                    'appId' => $config['appId'],
                    'stateDate' => $start,
                    'endDate' => $end
                ];
                if (!is_null($params)) {
                    $req['postBackParameter'] = $params;
                }
                $response = $client->query($url, $req);

                return new IteratorResponse(
                    $response['result'],
                    $response['pageSize'],
                    $response['postBackParameter']
                );
            }
        );
    }

    /**
     * 会员通用金额充值日志查询
     *
     * @param string $customerUid
     * @return array
     * @throws RequestException
     */
    public function queryCustomerRechargeLog(string $customerUid): array
    {
        $uri = '/pospal-api2/openapi/v1/customerOpenApi/queryCustomerRechargeLog';
        $config = $this->getConfig();
        $data = [
            'appId' => $config['appId'],
            'customerUid' => $customerUid
        ];

        return $this->query($uri, $data);
    }

    /**
     * 通过会员手机号查询会员
     *
     * @param string $tel
     * @return array
     * @throws RequestException
     */
    public function queryByTel(string $tel): array
    {
        $uri = '/pospal-api2/openapi/v1/customerOpenapi/queryBytel';
        $config = $this->getConfig();
        $data = [
            'appId' => $config['appId'],
            'customerTel' => $tel
        ];

        return $this->query($uri, $data);
    }

    /**
     * 查询所有会员分类（会员等级）
     *
     * @return array
     * @throws RequestException
     */
    public function queryAllCustomerCategory(): array
    {
        $uri = '/pospal-api2/openapi/v1/customerOpenApi/queryAllCustomerCategory';
        $config = $this->getConfig();
        $data = [
            'appId' => $config['appId']
        ];

        return $this->query($uri, $data);
    }

    /**
     * 批量修改会员类型（等级）
     *
     * @param array<array<string, mixed>> $customerCategories
     * @return bool
     * @throws RequestException
     * @throws \GuzzleHttp\Exception\GuzzleException|UnexpectedParamsException
     */
    public function batchUpdateCategory(array $customerCategories): bool
    {
        $uri = '/pospal-api2/openapi/v1/customerOpenApi/queryAllCustomerCategory';
        $config = $this->getConfig();
        $validator = new CustomerCategoriesValidator();

        foreach ($customerCategories as $customerCategory) {
            if (!is_array($customerCategory)) continue;

            $result = $validator->data($customerCategory)->check();
            if (!$result) {
                throw new UnexpectedParamsException($validator->getError());
            }
        }

        $data = [
            'appId' => $config['appId'],
            'customerCategories' => $customerCategories
        ];

        $response = $this->post($uri, $data);
        if (isset($response['errorCode']) && $response['status'] === 'error') {
            throw new RequestException(isset($response['messages']) ? $response['messages'][0] : '', $response['errorCode']);
        }

        return true;
    }

    /**
     * 修改会员密码
     *
     * @param string $customerUid
     * @param string $customerPassword
     * @return array
     * @throws RequestException
     * @throws UnexpectedParamsException
     */
    public function updateCustomerPassword(string $customerUid, string $customerPassword): bool
    {
        $uri = '/pospal-api2/openapi/v1/customerOpenApi/updateCustomerPassword';
        $config = $this->getConfig();
        $data = [
            'appId' => $config['appId'],
            'customerUid' => $customerUid,
            'customerPassword' => $customerPassword
        ];
        $validator = new CustomerPasswordValidator();
        $result = $validator->data($data)->check();
        if (!$result) {
            throw new UnexpectedParamsException($validator->getError());
        }

        $response = $this->post($uri, $data);
        if (isset($response['errorCode']) && $response['status'] === 'error') {
            throw new RequestException(isset($response['messages']) ? $response['messages'][0] : '', $response['errorCode']);
        }

        return true;
    }
}