<?php
namespace Acpay\Connector;

use Acpay\Helper\Sign;
use Acpay\Helper\Xml;
use Acpay\Exception\AcpayException;

abstract class Gateway
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    protected function post($url, $data)
    {
        $data['sign'] = Sign::generate($data, $this->config['key']);
        $xml = Xml::build($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/xml']);
        $result = curl_exec($ch);
        curl_close($ch);

        if (!$result) throw new AcpayException('接口请求失败');
        $res = Xml::parse($result);
        if (!isset($res['status']) || $res['status'] !== '0') {
            throw new AcpayException($res['message'] ?? '接口返回异常');
        }
        if (isset($res['sign']) && !Sign::verify($res, $this->config['key'])) {
            throw new AcpayException('返回签名验证失败');
        }
        return $res;
    }
}
