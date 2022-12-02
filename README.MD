# 银豹收银系统PHP接口助手

## 最低需求

- PHP: >=7.4
- guzzlehttp/guzzle: ^6.5

## 使用方法
```php
// 适用于API v1
$appV1 = \PospalHelper\Factory::v1([
    'baseUri' => '',
    'appId' => '',
    'appKey' => '',
    
    // 其他配置
    // ... 
]);

// 适用于API v2
$appV2 = \PospalHelper\Factory::v2([
    'baseUri' => '',
    'appId' => '',
    'appKey' => '',
    
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
    // 第n页数据
    $iter->next();
    $pageN = $iter->current();
    
    // 回到第一页
    $iter->rewind();
    // 设置最大循环次数
    $iter->setMax(20);
    
    // 如果需要批量调用可以使用foreach
    $data = [];
    foreach ($iter as $item) {
        $data[] = $item;
    }
} catch (PospalHelper\Core\Exception\RequestException $e) {
    // 打印返回的错误信息（如果有的话）
    echo $e->getMessage();
    // 打印返回的错误码
    echo $e->getCode();
    // 其他处理...
}
```
*\* v1、v2的区别见[交互格式说明-v1](http://pospal.cn/openplatform/interactiveformatdescription.html)、[交互格式说明-v2](http://pospal.cn/openplatform/interactiveformatdescriptionV2.html)。*

*\* v1、v2使用上并无特别需要注意的点，只是因为文档做了区分才分离出来，使用时根据自己所需使用即可。*

*\* 其他配置见[配置说明](./doc/Config.md)*
