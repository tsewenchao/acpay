<?php
namespace Acpay\Helper;

class Sign
{
    // 生成签名
    public static function generate($data, $key)
    {
        unset($data['sign']);
        ksort($data);
        $str = '';
        foreach ($data as $k => $v) {
            if ($v !== '' && $v !== null) {
                $str .= "{$k}={$v}&";
            }
        }
        $str .= "key={$key}";
        return strtoupper(hash('sha256', $str));
    }

    // 验证签名
    public static function verify($data, $key)
    {
        if (!isset($data['sign'])) return false;
        return self::generate($data, $key) === $data['sign'];
    }
}
