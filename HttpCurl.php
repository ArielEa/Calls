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
    private $defaultUrl = "https://zhao.b2c.omnixdb.com/wmsSync";

    private $header = "Content-type: application/xml";

    private  $params = [
        'app_key'       => 'testerp_appkey',
        'customerId'    => 'c12222',
        'format'        => 'xml',
        'method'        => 'taobao.qimen.deliveryorder.batchconfirm',
        'sign'          => '13C7B82226A6E396AFBA211DFBCB32F8',
        'sign_method'   => 'md5',
        'timestamp'     => '2020-10-20 15:15:00',
        'v'             => 2.0,
        'version'       => 1
    ];

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
  public function xmlRequest( $data, $requestType = 'get') {
        //初始化curl
        $ch = curl_init();
        $sendUrl = $this->defaultUrl .'?'. http_build_query( $this->params );
        //设置超时
        $header[] = "Content-type: text/xml";//定义content-type为xml
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $sendUrl);
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
