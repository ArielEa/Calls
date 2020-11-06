<?php

namespace Call\Post;

use Call\Request\ApiRequestInterface;
use Call\Request\RequestClientBase;

include_once "../Request/RequestClientBase.php";
include_once "../Request/ApiRequestInterface.php";

class DeliveryPost extends RequestClientBase implements ApiRequestInterface
{
    public function request($url, $postData, $postType = 'post')
    {
    }
}
