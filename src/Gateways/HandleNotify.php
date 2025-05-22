<?php
namespace Acpay\Gateways;

use Acpay\Helper\Xml;
use Acpay\Helper\Sign;
use Acpay\Exception\AcpayException;

class HandleNotify
{
    protected $config;
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function handle($xmlStr)
    {
        $data = Xml::parse($xmlStr);
        if (!$data) throw new AcpayException('通知数据异常');
        if (!Sign::verify($data, $this->config['key'])) throw new AcpayException('签名校验失败');
        if (!isset($data['status']) || $data['status'] != '0') throw new AcpayException('通知状态异常');
        return $data;
    }
}
