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
     * @return array|bool|mixed
     * @throws \Exception
     */
    public function confirm()
    {
        $remoteUrl = getParameters('remoteUrl');
        $qimenUrlConfig = getParameters('QimenUrlConfig');



        print_r( $qimenUrlConfig );
        die;
        return $this->xmlRequest( $this->qimenXML(), 'post' );
    }
}

$confirm = new DeliveryConfirmSingle();

$result = $confirm->confirm();

print_r( $result );

die;
