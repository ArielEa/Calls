<?php

include_once "ApiRequest.php";

class HttpCurl implements ApiRequest
{
	private $header = "Content-type: application/text";

	public function request( $url, $data, $requestType = 'post' )
	{
		$ch = curl_init ();

		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		$return = curl_exec ( $ch );
		curl_close ( $ch );
        
    return $return;
	}

	/**
   * @var $url
   * @var $data
   * @var $requestType
   * @return mixed|bool|array
   */
  public function xmlRequest($url, $data, $requestType = 'get') {
        //初始化curl
        $ch = curl_init();
        //设置超时
        $header[] = "Content-type: text/xml";//定义content-type为xml  
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if (strtolower($requestType) == 'post') {
            curl_setopt ( $ch, CURLOPT_POST, 1 );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        }
        $return = curl_exec($ch);
        curl_close($ch);
        return $return;
    }
}