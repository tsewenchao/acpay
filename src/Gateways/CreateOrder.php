<?php
namespace Acpay\Gateways;

use Acpay\Connector\Gateway;
use Acpay\Exception\AcpayException;

/**
 * 创建订单（网页跳转模式）
 */
class CreateOrder extends Gateway
{
    public function handle(array $params): array
    {
        if (empty($this->config['merchant_no'])) throw new AcpayException('缺少 merchant_no');
        $url = $this->config['api_url'] ?? $this->config['api_url1'] ?? null;
        if (empty($url)) throw new AcpayException('缺少 api_url 或 api_url1');

        $data = array_merge([
            'service'     => 'vmj',
            'version'     => '2.0',
            'charset'     => 'UTF-8',
            'sign_type'   => 'SHA-256',
            'merchant_no' => $this->config['merchant_no'],
            'nonce_str'   => md5(uniqid((string)mt_rand(), true)),
        ], $params);

        // 网页跳转模式下，必填参数
        $required = ['out_trade_no', 'body', 'total_fee', 'notify_url', 'callback_url'];
        foreach ($required as $field) {
            if (empty($data[$field])) throw new AcpayException("缺少参数: $field");
        }

        return $this->post($url, $data);
    }
}
