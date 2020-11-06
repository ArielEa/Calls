<?php

namespace Call\Request;

include_once "TopClient.php";

/**
 * Class RequestClientBase
 * @package Call\Request
 */
class RequestClientBase
{
    public function client( $params ) : TopClient
    {
        $client = new TopClient();
        $client->gatewayUrl   = $params['apiRemoteUrl'];
        $client->customerid   = $params['customerId'];
        $client->targetAppkey = $params['targetAppKey'];
        return $client;
    }
}
