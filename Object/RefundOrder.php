<?php

namespace Call\Object;

include_once DIR_PATH."/HttpCurl/HttpCurl.php";
include_once DIR_PATH."Post/RefundPost.php";

use Call\Enum\Enum;
use Call\HttpCurl\HttpCurl;
use Call\Post\RefundPost;

/**
 * Class RefundOrder
 * @package Call\Object
 */
class RefundOrder extends HttpCurl
{
    // method 入库单确认接口
    protected static $method = 'returnorder.confirm';

    // 暂时默认，没有其他平台
    protected static $platform = 'qimen';

    // 处理方式, 平台或者手动 (platform/diy)
    protected static $handleState = 'diy';

    /**
     * @param $method
     * @return mixed|string
     * @throws \Exception
     */
    protected function getRequestData($method)
    {
        $paraFile = Enum::getPara($method);
        return getParameters($method, $paraFile);
    }

    /**
     * 退货入库单确认处理
     * @param $method
     * @return array
     * @throws \Exception
     */
    public function confirm( $method ): array
    {
        $xmlData = $this->getRequestData($method);
        $req = $this->combineUrl(self::$method)->postData($xmlData, new RefundPost());
        $resp = [];
        foreach ( $req as $value ) {
            $XML = convertXml($value, 'orderLine',true);
            $resXml = $this->sendRequest($XML, 'post');
            $resData = $this->convertXml($resXml, $value['returnOrder']['returnOrderCode']);
            $resp[] = $resData;
        }
        return $resp;
    }

    /**
     * 转换退货入库单的处理结果
     * @param $xml
     * @param $code
     * @return array
     * @throws \Exception
     */
    protected function convertXml($xml, $code): array
    {
        $parseData = parseXml(trim($xml));
        $parseData['response_code'] = $code;
        return $parseData;
    }
}
