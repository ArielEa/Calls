<?php

namespace Call;

include_once "HttpCurl.php";
include_once "QimenXML.php";
include_once "CrossXML.php";
include_once "ConfTrait.php";
include_once "./Parameters.php";

use Call\ConfTrait;

/**
 * - 【 退货入库确认 】
 * Class DeliveryConfirmSingle
 * @package Call
 */
class DeliveryConfirmSingle extends HttpCurl
{
    use QimenXML;
    use CrossXML;
    use ConfTrait;
    // 发货单确认接口
    static protected $method = "deliveryorder.confirm";

    /**
     * - [ 获取发送url ]
     * @return string
     * @throws \Exception
     */
    protected function getSendUrl(): string
    {
        $remoteUrl = getParameters('remoteUrl');
        $qimenUrlConfig = getParameters('QimenUrlConfig');
        $urlRoute = getParameters('urlRoute');
        $sendUrl = $remoteUrl.$urlRoute;
        $qimenUrlConfig['method'] = self::$method;
        return $sendUrl."?".http_build_query($qimenUrlConfig);
    }

    /**
     * @return array|bool|mixed
     * @throws \Exception
     */
    public function confirm()
    {
        $url = $this->getSendUrl();
        $xml = file_get_contents("XML/Qimen.xml");
//        $data = $this->qimenXML();
        return $this->sendRequest( $xml, $url, 'post' );
    }
}

$confirm = new DeliveryConfirmSingle();

$result = $confirm->confirm();

print_r( $result );

die;
