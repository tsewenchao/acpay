<?php
namespace Acpay\Helper;

class Xml
{
    // 数组转xml
    public static function build($data)
    {
        $xml = '<xml>';
        foreach ($data as $k => $v) {
            $xml .= "<{$k}><![CDATA[{$v}]]></{$k}>";
        }
        $xml .= '</xml>';
        return $xml;
    }

    // xml转数组
    public static function parse($xml)
    {
        if (!$xml) return [];
        $res = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        return json_decode(json_encode($res), true) ?: [];
    }
}
