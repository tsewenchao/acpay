<?php

namespace Acpay;

use Acpay\Exception\AcpayException;
use Acpay\Connector\GatewayInterface;

/**
 * ACPay SDK 主入口
 */
class Acpay
{
    /** 配置信息 */
    protected array $config = [];

    /** 辅助类白名单（格式化后的类名） */
    protected static array $nonGatewayClasses = ['Notify'];

    /**
     * 构造函数
     * @param array $config 配置参数
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * 动态调用网关或辅助类
     * @param string $method 方法名
     * @param array $arguments 参数
     */
    public function __call(string $method, array $arguments)
    {
        $params = $arguments[0] ?? [];
        $gateway = self::getGatewayClass($method);

        if (!class_exists($gateway)) {
            throw new AcpayException("Gateway [{$method}] 不存在：{$gateway}");
        }

        $formattedMethod = self::formatGatewayName($method);
        $instance = new $gateway($this->config);

        if (!in_array($formattedMethod, self::$nonGatewayClasses, true) && !$instance instanceof GatewayInterface) {
            throw new AcpayException("Gateway [{$method}] 必须实现 GatewayInterface");
        }

        if (in_array($formattedMethod, self::$nonGatewayClasses, true)) {
            return $instance;
        }

        return $instance->handle($params);
    }

    /**
     * 静态调用方式
     * @param string $method 方法名
     * @param array $arguments 参数
     */
    public static function __callStatic(string $method, array $arguments)
    {
        $params = $arguments[1] ?? [];
        $config = $arguments[0] ?? [];
        $gateway = self::getGatewayClass($method);

        if (!class_exists($gateway)) {
            throw new AcpayException("Gateway [{$method}] 不存在：{$gateway}");
        }

        $formattedMethod = self::formatGatewayName($method);
        $instance = new $gateway($config);

        if (!in_array($formattedMethod, self::$nonGatewayClasses, true) && !$instance instanceof GatewayInterface) {
            throw new AcpayException("Gateway [{$method}] 必须实现 GatewayInterface");
        }

        if (in_array($formattedMethod, self::$nonGatewayClasses, true)) {
            return $instance;
        }

        return $instance->handle($params);
    }


    /**
     * 方法名格式化成类名
     * @param string $gateway
     */
    public static function formatGatewayName(string $gateway): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $gateway)));
    }

    /**
     * 获取网关类名（含命名空间）
     * @param string $gateway
     */
    protected static function getGatewayClass(string $gateway): string
    {
        $gateway = self::formatGatewayName($gateway);
        return __NAMESPACE__ . '\\Gateways\\' . $gateway;
    }
}
