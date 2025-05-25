<?php
namespace Acpay\Connector;

use Acpay\Helper\Sign;
use Acpay\Helper\Xml;
use Acpay\Exception\AcpayException;

/**
 * 网关基类
 */
abstract class Gateway implements GatewayInterface
{
    protected array $config;

    /**
     * @param array $config 必须有 'merchant_no', 'key', 'api_url' (或各接口自定义)
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * 发起POST请求
     */
    protected function post(string $url, array $data): array
    {
        if (empty($url)) {
            throw new AcpayException('API请求地址不能为空');
        }
        $data['sign'] = Sign::generate($data, $this->config['key']);
        $xml = Xml::build($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/xml']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // 支持自定义cURL选项
        if (!empty($this->config['curl_options']) && is_array($this->config['curl_options'])) {
            curl_setopt_array($ch, $this->config['curl_options']);
        }

        try {
            $result = curl_exec($ch);
            if ($result === false) {
                $err = curl_error($ch);
                $eno = curl_errno($ch);
                throw new AcpayException("cURL请求失败: {$err} ({$eno})");
            }
        } finally {
            curl_close($ch);
        }

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
