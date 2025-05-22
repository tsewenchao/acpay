<?php
namespace Acpay;

use Acpay\Gateways\CreateOrder;
use Acpay\Gateways\QueryOrder;
use Acpay\Gateways\CancelOrder;
use Acpay\Gateways\CaptureOrder;
use Acpay\Gateways\RefundOrder;
use Acpay\Gateways\HandleNotify;

class Acpay
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function createOrder()
    {
        return new CreateOrder($this->config);
    }

    public function queryOrder()
    {
        return new QueryOrder($this->config);
    }

    public function cancelOrder()
    {
        return new CancelOrder($this->config);
    }

    public function captureOrder()
    {
        return new CaptureOrder($this->config);
    }

    public function refundOrder()
    {
        return new RefundOrder($this->config);
    }

    public function handleNotify()
    {
        return new HandleNotify($this->config);
    }
}
