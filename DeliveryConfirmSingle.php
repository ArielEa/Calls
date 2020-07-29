<?php
include_once "HttpCurl.php";
include_once "QimenXML.php";
include_once "CrossXML.php";


class DeliveryConfirmSingle extends HttpCurl
{
  use QimenXML;
  use CrossXML;

  static protected $url = "xxxxxxxx/wmsSync";

  public function single() 
  {
  	print_r( $remoteUrl );

  	die;

    return $this->xmlRequest( self::$url, $this->QimenXML(), 'post' );
  }
}

$confirm = new DeliveryConfirmSingle();
$result = $confirm->single();
print_r( $result );
die;