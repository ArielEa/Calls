<?php

namespace Call;

include_once "ApiRequest.php";

/**
 * 纯发送请求，不加奇门验证请求，但是参数保持一致
 * Class HttpCurl
 * @package Call
 */
class HttpCurl implements ApiRequest
{
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
     * @param $data
     * @param $url
     * @param string $requestType
     * @return bool|string
     */
	public function sendRequest($data, $url, $requestType = 'get')
    {
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
