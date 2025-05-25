<?php
namespace Acpay\Helper;

/**
 * XML工具类
 */
class Xml
{
    /**
     * 数组转XML
     */
    public static function build(array $data): string
    {
        $xml = '<xml>';
        foreach ($data as $k => $v) {
            $xml .= "<{$k}><![CDATA[{$v}]]></{$k}>";
        }
        $xml .= '</xml>';
        return $xml;
    }

    /**
     * XML转数组
     */
    public static function parse(string $xml): array
    {
        if (!$xml) return [];
        libxml_disable_entity_loader(true);
        $res = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        return $res ? json_decode(json_encode($res), true) : [];
    }
}
