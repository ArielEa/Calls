<?php
namespace Call\Object;

include_once DIR_PATH."/HttpCurl/HttpCurl.php";
include_once DIR_PATH."Parameters.php";
include_once DIR_PATH."Enum/Enum.php";
include_once DIR_PATH."Post/EntryOrderPost.php";
include_once DIR_PATH."Base/Project.php";

use Call\HttpCurl\HttpCurl;
use Call\Enum\Enum;
use Call\Post\EntryOrderPost;

/**
 * - [ 入库单模版 ]
 * Class EntryOrder
 * @package MethodRequest
 */
class EntryOrder extends HttpCurl
{
    // method 入库单确认接口
    protected static $method = 'entryorder.confirm';

    // 暂时默认，没有其他平台
    protected static $platform = 'qimen';

    // 处理方式, 平台或者手动 (platform/diy)
    protected static $handleState = 'diy';

    /**
     * @param $method
     * @return mixed|string
     * @throws \Exception
     */
    protected function requestData($method)
    {
        $paraFile = Enum::getPara($method);

        return getParameters($method, $paraFile);
    }

    /**
     * @param $method
     * @return array
     * @throws \Exception
     */
    public function confirm($method) : array
    {
        $xmlData = $this->requestData($method);

        $req = $this->combineUrl(self::$method)->postData($xmlData, new EntryOrderPost());

        $resp = [];
        foreach ( $req as $value ) {
            $XML = convertXml($value);
            $resXml = $this->sendRequest($XML, 'post');
            $resData = $this->convertXml($resXml);
            $resp[] = $resData;
        }
        return $resp;
    }

    /**
     * @param $xml
     * @return array
     * @throws \Exception
     */
    protected function convertXml($xml): array
    {
        return parseXml($xml);
    }
}
