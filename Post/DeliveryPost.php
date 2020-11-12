<?php

namespace Call\Post;


include_once DIR_PATH."/Request/RequestClientBase.php";
include_once DIR_PATH."/Request/ApiRequestInterface.php";
include_once DIR_PATH."/Request/DeliveryConfirmRequest.php";

use Call\Request\ApiRequestInterface;
use Call\Request\RequestClientBase;

/**
 * Class DeliveryPost
 * @package Call\Post
 */
class DeliveryPost extends RequestClientBase implements ApiRequestInterface
{
    protected $postData = []; // 发货单基础信息

    public function request($url, $postData, $postType = 'post', $status = 'diy')
    {
        $this->postData = $postData;

        print_r( $this->postData );

        die;
        return [];
    }
}
