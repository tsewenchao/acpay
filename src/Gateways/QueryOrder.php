<?php
namespace Acpay\Gateways;

use Acpay\Connector\Gateway;

class QueryOrder extends Gateway
{
    public function handle($params)
    {
        $data = array_merge([
            'service'     => 'unified.trade.query',
            'version'     => '2.0',
            'charset'     => 'UTF-8',
            'sign_type'   => 'SHA-256',
            'merchant_no' => $this->config['merchant_no'],
            'nonce_str'   => md5(uniqid()),
        ], $params);

        return $this->post($this->config['api_url2'] . '/Query', $data);
    }
}
