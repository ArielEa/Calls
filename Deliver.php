<?php

include_once "HttpCurl.php";

class Deliver extends HttpCurl
{
	private $code = [];

	private $url = "xxxxxxxxx/ApiQueue/DirectDelivery";

	public function __construct( $code ) 
	{
		$this->code = $code;
	}


	public function deliver()
	{
		$code = [ 'deliveryCode' => implode(',', $this->code) ];

		$result = $this->request( $this->url, $code );

		print_r( $result );die;

		die;

		print_r( json_decode( $result, true ) );die;

		return $result;
	}
}

/**
 * @param $argc 参数个数
 * @param $argv 参数
 */
if ( $argc == 1 )  {
	print_r([ 'code' => 500, 'msg' => '请传入参数' ]); die;
}

unset($argv[0]);

$codes = array_values($argv);

$deliver = new Deliver( $codes );

print_r( $deliver->deliver() );