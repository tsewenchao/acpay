<?php
namespace Acpay\Gateways;

use Acpay\Connector\Gateway;
use Acpay\Exception\AcpayException;

/**
 * 退款订单
 */
class RefundOrder extends Gateway
{
    public function handle(array $params): array
    {
        if (empty($this->config['merchant_no'])) throw new AcpayException('缺少 merchant_no');
        $url = $this->config['api_url2'] ?? null;
        if (empty($url)) throw new AcpayException('缺少 api_url2');

        $data = array_merge([
            'service'     => 'unified.trade.refund',
            'version'     => '2.0',
            'charset'     => 'UTF-8',
            'sign_type'   => 'SHA-256',
            'merchant_no' => $this->config['merchant_no'],
            'nonce_str'   => md5(uniqid((string)mt_rand(), true)),
        ], $params);

        $required = ['out_trade_no', 'refund_fee'];
        foreach ($required as $field) {
            if (empty($data[$field])) throw new AcpayException("缺少参数: $field");
        }

        return $this->post($url . '/Refund', $data);
    }
}
