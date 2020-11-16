<?php

namespace Call\Object;

include_once DIR_PATH."/HttpCurl/HttpCurl.php";
include_once DIR_PATH."Enum/Enum.php";
include_once DIR_PATH."Parameters.php";
include_once DIR_PATH."/Post/DeliveryPost.php";

use Call\Enum\Enum;
use Call\Post\DeliveryPost;
use Call\HttpCurl\HttpCurl;

/**
 * - 【 发货单 】
 * Class DeliveryConfirmSingle
 * @package Call
 */
class DeliveryOrder extends HttpCurl
{
    // 发货单确认接口
    static protected $method = "deliveryorder.confirm";

    // 默认平台奇门
    protected static $platform = 'qimen';

    // 处理方式, 平台或者手动 (platform/diy)
    protected static $handleState = 'diy';

    /**
     * @param $method
     * @return mixed|string
     * @throws \Exception
     */
    protected function requestData( $method )
    {
        $paraFile = Enum::getPara($method);
        return getParameters($method, $paraFile);
    }

    /**
     * @param $method
     * @return array|bool|mixed
     * @throws \Exception
     */
    public function confirm($method):array
    {
        $xmlData = $this->requestData($method);
        $req = $this->combineUrl( self::$method )->postData( $xmlData, new DeliveryPost() );
        $resp = [];
        foreach ( $req as $value ) {
            $XML = convertXml($value, 'orderLine',true);
            $resXml = $this->sendRequest($XML, 'post');
            $resData = $this->convertXml($resXml, $value['deliveryOrder']['deliveryOrderCode']);
            $resp[] = $resData;
        }
        return $resp;
    }

    /**
     * @param $xml
     * @param $code
     * @return array
     * @throws \Exception
     */
    protected function convertXml($xml, $code): array
    {
        $parseData = parseXml($xml);
        $parseData['response_code'] = $code;
        return $parseData;
    }
}

