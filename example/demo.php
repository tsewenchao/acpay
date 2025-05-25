<?php
require '../vendor/autoload.php';

use Acpay\Acpay;

$config = [
    'merchant_no' => 'xxxx',
    'key'         => 'xxxx',
    'api_url'     => 'https://aiodir.payloop.com.tw',
    'api_url2'    => 'https://aio.payloop.com.tw',
];

$acpay = new Acpay($config);

// 下单
$res = $acpay->createOrder()->handle([
    'out_trade_no' => 'T' . date('YmdHis'),
    'body'         => '测试商品',
    'total_fee'    => 1,
    'notify_url'   => 'https://www.xxx.com/notify',
    'callback_url' => 'https://www.xxx.com/callback',
]);
print_r($res);

// 查询
$res = $acpay->queryOrder()->handle([
    'out_trade_no' => '你的订单号',
]);
print_r($res);

// 退款
$res = $acpay->refundOrder()->handle([
    'transaction_id' => '平台订单号',
    'out_refund_no'  => '退款单号',
    'total_fee'      => 1,
    'refund_fee'     => 1,
]);
print_r($res);
