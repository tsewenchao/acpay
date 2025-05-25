<?php
namespace Acpay\Gateways;

use Acpay\Helper\Xml;
use Acpay\Helper\Sign;
use Acpay\Exception\AcpayException;

/**
 * 支付通知回调处理
 */
class Notify
{
    protected array $config;
    protected ?array $data = null;

    public function __construct(array $config)
    {
        if (empty($config['key'])) {
            throw new AcpayException('缺少 key');
        }
        $this->config = $config;
    }

    /**
     * 获取并验签回调通知数据
     * @throws AcpayException
     * @return array 验签通过且已解析的通知数据
     */
    public function getData(): array
    {
        if ($this->data !== null) {
            return $this->data;
        }

        // 自动获取原始回调内容
        $rawXml = file_get_contents('php://input');

        if (!$rawXml) {
            throw new AcpayException('通知内容为空');
        }

        $data = Xml::parse($rawXml);
        
        if (!$data || !isset($data['sign'])) {
            throw new AcpayException('通知数据无效或缺少签名');
        }

        if (!Sign::verify($data, $this->config['key'])) {
            throw new AcpayException('通知验签失败');
        }

        $this->data = $data;
        return $data;
    }

    /**
     * 响应微信/银联等格式的应答XML
     * @param bool $ok
     * @param string $msg
     * @return string
     */
    public function response(bool $ok, string $msg = 'OK'): string
    {
        return Xml::build([
            'return_code' => $ok ? 'SUCCESS' : 'FAIL',
            'return_msg'  => $msg
        ]);
    }
}
