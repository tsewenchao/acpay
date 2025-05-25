<?php

namespace Acpay\Helper;

/**
 * 签名工具类
 */
class Sign
{
    /**
     * 生成签名
     */
    public static function generate(array $data, string $key): string
    {
        unset($data['sign']);
        ksort($data);
        $str = '';
        foreach ($data as $k => $v) {
            if ($v !== '' && $v !== null && !is_array($v) && !is_object($v)) {
                $str .= "{$k}={$v}&";
            }
        }
        $str .= "key={$key}";
        $sign = strtoupper(hash('sha256', $str));
        return $sign;
    }


    /**
     * 验证签名
     */
    public static function verify(array $data, string $key): bool
    {
        if (!isset($data['sign'])) return false;
        return self::generate($data, $key) === $data['sign'];
    }
}
