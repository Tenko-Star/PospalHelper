# 配置说明

## 基础配置

- baseUri： 接口地址前缀
- appId：接口请求所需的appId
- appKey：接口请求所需的appKey

**\* 基础配置中的信息由销售人员提供，使用前请先联系销售人员获取请求凭证**

## 请求配置

- http.proxy：设置代理地址
- http.verify：是否开启验证
- http.*：其他的Guzzle配置在Guzzle原本配置名称前加`http.`即可。
如：想要开启Guzzle`debug`可设置成`'http.debug' => true`

*\*更多配置见[Guzzle配置文档](https://docs.guzzlephp.org/en/stable/)、
[Guzzle中文文档](https://guzzle-cn.readthedocs.io/zh_CN/latest/)*

## 配置示例
```php
$config = [
    // 基础配置
    'baseUri' => 'https://area62-win.pospal.cn:443',
    'appId' => '',
    'appKey' => '',

    // Guzzle 配置
    // 配置代理以便抓包分析
    'http.proxy' => 'http://127.0.0.1:9999',
    // 请求的连接为https时，关闭验证可以避免在使用http代理抓包时出现问题
    'http.verify' => false
]
```