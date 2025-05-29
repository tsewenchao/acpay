<?php
require 'vendor/autoload.php'; // 请确保自动加载已正确配置

use Acpay\Acpay;
use Acpay\Exception\AcpayException;

// 配置参数（请替换为你的商户号和密钥）
$config = [
    'merchant_no' => 'M123456789',
    'key'         => 'your_secret_key',
    // 'sandbox'   => true, // 测试环境请取消注释
];

/**
 * 创建订单示例
 */
function demoCreateOrder($config)
{
    $acpay = new Acpay($config);
    try {
        $params = [
            'out_trade_no' => 'ORDER' . time(),        // 商户订单号
            'body'         => '测试商品',               // 商品描述
            'total_fee'    => 1000,                    // 金额 单位: 新台币元
            'notify_url'   => 'https://yourdomain.com/api/pay/notify', // 异步回调地址
            'callback_url' => 'https://yourdomain.com/api/pay/return', // 同步通知地址
        ];
        $result = $acpay->createOrder($params);
        echo "下单成功，返回数据：\n";
        print_r($result);
        return $params['out_trade_no'];
    } catch (AcpayException $e) {
        echo "下单异常：" . $e->getMessage() . PHP_EOL;
    }
}

/**
 * 查询订单示例
 */
function demoQueryOrder($config, $out_trade_no)
{
    $acpay = new Acpay($config);
    try {
        $params = [
            'out_trade_no' => $out_trade_no,
        ];
        $result = $acpay->queryOrder($params);
        echo "查询订单结果：\n";
        print_r($result);
        return $result;
    } catch (AcpayException $e) {
        echo "查询订单异常：" . $e->getMessage() . PHP_EOL;
    }
}

/**
 * 退款订单示例
 */
function demoRefundOrder($config, $transaction_id, $total_fee)
{
    $acpay = new Acpay($config);
    try {
        $params = [
            'transaction_id' => $transaction_id,
            'out_refund_no'  => 'REFUND' . time(),
            'refund_fee'     => 500,           // 部分退款，单位: 新台币元
            'total_fee'      => $total_fee,    // 原订单金额
        ];
        $result = $acpay->refundOrder($params);
        echo "退款成功，返回数据：\n";
        print_r($result);
        return $result;
    } catch (AcpayException $e) {
        echo "退款异常：" . $e->getMessage() . PHP_EOL;
    }
}

function handleNotify($config)
{
    try {
        // 获取并验签通知（SDK会自动读取php://input）
        $notify = Acpay::notify($config);
        $data = $notify->getData();

        // 业务处理：如更新订单状态、发货、记日志等
        // 这里仅做简单示例，生产环境请做好幂等校验！
        // file_put_contents('acpay_notify.log', var_export($data, true), FILE_APPEND);

        // 你可以根据$data['out_trade_no']等字段处理业务

        // 返回ACPay指定的成功响应
        echo $notify->response(true, 'OK');
    } catch (AcpayException $e) {
        // 验签失败或其它异常，返回失败
        $failResponse = (new \Acpay\Gateways\Notify($config))->response(false, $e->getMessage());
        echo $failResponse;
    }
}