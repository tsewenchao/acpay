<?php
namespace Acpay\Gateways;

use Acpay\Connector\Gateway;
use Acpay\Exception\AcpayException;

/**
 * 捕获订单（請款）
 */
class CaptureOrder extends Gateway
{
    public function handle(array $params): array
    {
        if (empty($this->config['merchant_no'])) {
            throw new AcpayException('缺少 merchant_no');
        }

        $url = $this->getApiRoot2() . '/Capture';

        $data = array_merge([
            'service'     => 'unified.trade.capture',
            'version'     => '2.0',
            'charset'     => 'UTF-8',
            'sign_type'   => 'SHA-256',
            'merchant_no' => $this->config['merchant_no'],
            'nonce_str'   => md5(uniqid((string)mt_rand(), true)),
        ], $params);
        
        $required = ['transaction_id', 'settle_fee'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new AcpayException("缺少参数: $field");
            }
        }

        return $this->post($url, $data);
    }
}
