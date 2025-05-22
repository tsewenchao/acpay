# ACpay PHP SDK

一个用于接入 ACpay 支付平台的 PHP SDK，支持创建订单、查询订单、退款、请款、取消订单、处理回调等功能。

## 安装

```bash
composer require tsewenchao/acpay
```

## 使用示例

```php
use Tsewenchao\Acpay\Acpay;

$config = [
    'merchant_no'   => '你的商户号',
    'key'           => '你的密钥',
    'api_url'       => 'https://aiodir.payloop.com.tw',
    'api_url2'      => 'https://aio.payloop.com.tw',
    'notify_url'    => 'https://你的域名/notify',
    'callback_url'  => 'https://你的域名/callback',
];

$acpay = new Acpay($config);

// 创建订单
$payUrl = $acpay->createOrder('ORDER123456', 100, '商品描述');
echo "跳转支付链接: $payUrl";

// 查询订单
// $result = $acpay->queryOrder('TRANSACTION_ID');
```

## 方法说明

| 方法            | 参数说明                              | 说明                         |
|-----------------|---------------------------------------|------------------------------|
| `createOrder`   | 订单编号、金额、商品描述               | 创建订单并获取跳转链接       |
| `queryOrder`    | ACpay 交易号                          | 查询订单状态                 |
| `cancelOrder`   | 商户订单号                            | 取消订单                     |
| `captureOrder`  | 交易号、订单号、金额                  | 请款操作                     |
| `refundOrder`   | 交易号、订单号、退款单号、金额、原总金额 | 发起退款                     |
| `handleNotify`  | 原始 XML 内容                         | 处理异步通知并验签           |

## 返回格式

所有接口返回 ACpay XML 响应解析后的数组。

## 授权协议

MIT License
