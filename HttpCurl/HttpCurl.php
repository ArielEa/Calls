<?php

namespace Call\HttpCurl;

include_once DIR_PATH."/HttpCurl/ApiRequest.php";
include_once DIR_PATH."Parameters.php";
include_once DIR_PATH."Enum/Enum.php";
include_once DIR_PATH."Post/EntryOrderPost.php";

use Call\Enum\Enum;
use Call\HttpCurl\ApiRequest;

/**
 * 纯发送请求，不加奇门验证请求，但是参数保持一致
 * Class HttpCurl
 * @package Call
 */
class HttpCurl implements ApiRequest
{
    protected $sendUrl = ""; // 发送的url

    /**
     * @param $method
     * @return $this
     * @throws \Exception
     */
    public function combineUrl($method): HttpCurl
    {
        $methodFile = Enum::getPara();
        ## todo:: 直接拿取配置参数，不解析
        $parameters = matchParameters($methodFile);
        $qimenUrlConfig = $parameters['QimenUrlConfig'];
        $sendUrl = $parameters['remoteUrl'].$parameters['urlRoute'];
        $qimenUrlConfig['method'] = $method;
        $this->sendUrl = $sendUrl."?".http_build_query($qimenUrlConfig);
        return $this;
    }

    /**
     * 获取配置信息
     * @param $rows
     * @param $object
     * @return array
     */
    protected function postData($rows, $object): array
    {
        $entryPost = [];

        foreach ($rows as $key => $value) {
            $entryPost[] = $object->request($this->sendUrl, $value, 'post', 'diy');
        }
        return $entryPost;
    }

	public function request( $url, $data, $requestType = 'post' )
	{
        $url = $this->sendUrl;
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
	public function sendRequest($data, $requestType = 'get')
    {
        $url = $this->sendUrl;
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
