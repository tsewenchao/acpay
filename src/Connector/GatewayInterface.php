<?php
namespace Acpay\Connector;

use Acpay\Exception\AcpayException;

/**
 * 网关接口
 */
interface GatewayInterface
{
    /**
     * 业务处理统一入口
     * @param array $params
     * @return array
     * @throws AcpayException
     */
    public function handle(array $params): array;
}
