<?php

include_once 'function.php';

use PospalHelper\Factory;
use PHPUnit\Framework\TestCase;

class MainTest extends TestCase
{

    public function testExample()
    {
        $appV1 = Factory::v1([
            // 基础配置
            'baseUri' => env('BaseUri'),
            'appId' => env('AppId'),
            'appKey' => env('AppKey'),

            // Guzzle 配置
            // 配置代理以便抓包分析
            'http.proxy' => 'http://127.0.0.1:9999',
            'http.verify' => false

            // 其他配置
            // ...
        ]);

        // 示例：返回数据的接口-根据会员手机号查询会员
        try {
            // 如果成功调用，返回的数据为接口结果，不包含message/status等额外信息
            $data = $appV1->customer->queryByTel("13800008888");
            // $data => [
            //      "customerUid" => "",
            //      "categoryName" => "金卡",
            //      "number" => "银豹收银",
            //      "name" => "OpenApi创建",
            //      ...
            //  ]
            $this->assertIsArray($data);
            $this->assertArrayHasKey('customerUid', $data);
        } catch (PospalHelper\Core\Exception\RequestException $e) {
            // 打印返回的错误信息（如果有的话）
            echo $e->getMessage();
            // 打印返回的错误码
            echo $e->getCode();
            // 其他处理...
        }

        // 示例：没有返回数据的接口-修改会员密码
        try {
            // 如果成功调用，返回true
            $data = $appV1->customer->updateCustomerPassword("1393920057254848127", "123456");
            // $data => true
            $this->assertTrue($data);
        } catch (PospalHelper\Core\Exception\RequestException $e) {
            // 打印返回的错误信息（如果有的话）
            echo $e->getMessage();
            // 打印返回的错误码
            echo $e->getCode();
            // 其他处理...
        }

        // 示例：带分页的接口-分页查询全部会员
        try {
            // 如果成功调用，返回一个CustomerIterator对象
            $iter = $appV1->customer->queryCustomerPages();
            // 第一页数据
            $page1 = $iter->current();
            $this->assertIsArray($page1);
            $this->assertArrayHasKey('customerUid', $page1[0]);
            // 第n页数据
            $iter->next();
            $pageN = $iter->current();
            $this->assertIsArray($pageN);
            $this->assertArrayHasKey('customrUid', $pageN);
            // 回到请求开头
            $iter->rewind();
            // 设置最大循环次数
            $iter->setMax(20);

            // 如果需要批量调用可以使用foreach
            $data = [];
            foreach ($iter as $item) {
                $data[] = $item;
            }

            $this->assertNotEmpty($data);

            $json = json_encode($data);
            file_put_contents('customer_data.json', $json);

        } catch (PospalHelper\Core\Exception\RequestException $e) {
            // 打印返回的错误信息（如果有的话）
            echo $e->getMessage();
            // 打印返回的错误码
            echo $e->getCode();
            // 其他处理...
        }
    }

    public function testAccess() {
        $app = Factory::v1([
            // 基础配置
            'baseUri' => env('BaseUri'),
            'appId' => env('AppId'),
            'appKey' => env('AppKey'),

            'http.proxy' => 'http://127.0.0.1:9999',
            'http.verify' => false

            // 其他配置
            // ...
        ]);

        try {
            $result = $app->access->queryAccessTimes();
            $this->assertIsArray($result);
            file_put_contents('access_times.json', json_encode($result));

            $result = $app->access->queryDailyAccessTimesLog(new DateTimeImmutable('2022-12-1'), new DateTimeImmutable('2022-12-6'));
            $this->assertIsArray($result);
            file_put_contents('daily_access_times.json', json_encode($result));
        } catch (\PospalHelper\Core\Exception\PospalException $e) {
            file_put_contents('test_access_trace.json', json_encode($e->getTrace()));
            $this->fail($e->getMessage());
        }
    }
}
