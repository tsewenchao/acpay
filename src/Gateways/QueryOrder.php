<?php
namespace Acpay\Gateways;

use Acpay\Connector\Gateway;
use Acpay\Exception\AcpayException;

/**
 * 查询订单
 */
class QueryOrder extends Gateway
{
    public function handle(array $params): array
    {
        if (empty($this->config['merchant_no'])) {
            throw new AcpayException('缺少 merchant_no');
        }

        $url = $this->getApiRoot2() . '/Query';

        $data = array_merge([
            'service'     => 'unified.trade.query',
            'version'     => '2.0',
            'charset'     => 'UTF-8',
            'sign_type'   => 'SHA-256',
            'merchant_no' => $this->config['merchant_no'],
            'nonce_str'   => md5(uniqid((string)mt_rand(), true)),
        ], $params);

        // out_trade_no 或 transaction_id 至少一项必填
        if (empty($data['out_trade_no']) && empty($data['transaction_id'])) {
            throw new AcpayException('缺少参数: out_trade_no 或 transaction_id 必须至少一个');
        }

        return $this->post($url, $data);
    }
}
