# ACPay PHP SDK

[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

ACPay PHP SDK 是一个适用于 ACPay 支付网关的轻量级 PHP SDK，支持订单创建、查询、退款、撤销、通知处理等常用接口。适用于 Laravel/ThinkPHP/Yii/Swoole 及原生 PHP 项目。

---

## 目录

- [安装](#安装)
- [快速开始](#快速开始)
- [接口说明](#接口说明)
- [配置说明](#配置说明)
- [支付通知与回调](#支付通知与回调)
- [异常处理](#异常处理)
- [License](#license)

---

## 安装

推荐使用 Composer：

    composer require tsewen/acpay

或者手动引入源码。

---

## 快速开始

    use Acpay\Acpay;

    $config = [
        'merchant_no' => 'YOUR_MERCHANT_NO',
        'key'         => 'YOUR_API_KEY',
        'sandbox'     => true, // 沙箱环境 true，生产环境 false
    ];

    $acpay = new Acpay($config);

    // 创建订单
    try {
        $order = $acpay->createOrder([
            'out_trade_no'  =>  'ORDER20240601',
            'body'          =>  '商品描述',
            'total_fee'     =>  100, // 单位：元（TWD）
            'notify_url'    =>  'https://your.site/notify',
            'callback_url'  =>  'https://your.site/return'
        ]);
        // $order 数组格式，包含支付跳转参数
    } catch (\Acpay\Exception\AcpayException $e) {
        echo '下单失败：' . $e->getMessage();
    }

---

## 接口说明

| 方法           | 说明           | 主要参数                                |
|----------------|----------------|-----------------------------------------|
| createOrder    | 创建订单       | out_trade_no, body, total_fee, notify_url, callback_url |
| queryOrder     | 查询订单       | out_trade_no 或 transaction_id          |
| refundOrder    | 退款           | transaction_id, out_refund_no, refund_fee, total_fee |
| cancelOrder    | 撤销订单       | out_trade_no                            |
| captureOrder   | 捕获（請款）   | transaction_id, settle_fee              |
| notify         | 回调处理类     | 自动获取                                 |

#### 示例：查询订单

    $result = $acpay->queryOrder([
        'out_trade_no' => 'ORDER20240601',
    ]);

#### 静态调用

    $result = Acpay::queryOrder($config, ['out_trade_no' => 'ORDER20240601']);

---

## 配置说明

| 配置项       | 说明             | 是否必填 |
|--------------|------------------|----------|
| merchant_no  | 商户号           | 是       |
| key          | API 密钥         | 是       |
| sandbox      | 是否沙箱         | 否       |
| curl_options | cURL 选项数组    | 否       |

---

## 支付通知与回调

**回调通知验签处理示例**：

    $notify = $acpay->notify();
    try {
        $data = $notify->getData();
        // 验签成功，$data 为回调内容数组
        // TODO: 订单处理逻辑
        echo $notify->response(true); // 回复SUCCESS
    } catch (\Acpay\Exception\AcpayException $e) {
        echo $notify->response(false, $e->getMessage());
    }

> `notify()` 返回的是 Notify 类实例，需手动调用 `getData()` 获取并进行验签。

---

## 异常处理

所有接口调用失败会抛出 `Acpay\Exception\AcpayException`，请注意捕获。

---

## License

MIT
