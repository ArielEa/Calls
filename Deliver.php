<?php

namespace Call;

include_once "HttpCurl.php";

class Deliver extends HttpCurl
{
	private $code = [];

	private $url = "https://xxxxx";

	public function __construct( $code )
	{
		$this->code = $code;
	}


	public function deliver()
	{
		$code = [ 'deliveryCode' => implode(',', $this->code) ];

		$result = $this->request( $this->url, $code );

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


// $codes = [];

// $counts = count($codes);
// $start = 0;
// $line = 13;
// $ceil = ceil( $counts / $line );


// while ( $ceil > 0 )
// {
// 	$arr = $codes;

// 	$array = array_splice( $arr, $start, $line );

// 	// echo $ceil.' : '.$start. '--------->' .$line.PHP_EOL;


// 	$deliver = new Deliver( $array );

// 	$res = $deliver->deliver();

// 	$ceil-- ;
// 	$start += $line;

// }

// die;

$codes = array_values($argv);

$deliver = new Deliver( $codes );

print_r( $deliver->deliver() );
