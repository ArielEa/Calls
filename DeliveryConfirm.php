<?php

namespace MethodRequest\Call;

/**
 * @package DeliveryConfirm
 */
class DeliveryConfirm 
{
  /**
   * @param -
   * @return string\bool
   */
  public function delivery_confirm() 
  {
    $url = "xxxxxxxxx/wmsSync";

    $data = [
      200728208819748, 200728539103988, 2007285002709, 200728277532885, 200728617946
    ];

    $i = 1;

    $trackingNo = "2147267111670";

    foreach ($data as $key => $value) {
        $xml = $this->body( $value, $trackingNo );

        $trackingNo += 1;

        $result = $this->httpCurl($url, $xml, 'post');
        
        echo  "{$i}--->{$value}------>{$result}". PHP_EOL;

        $i++;
    }

    die;
    return $result;
  }

  public function body( $code, $trackingNo ) {
    $body = <<<XML
<?xml version="1.0" encoding="utf-8"?>

<request>
  <deliveryOrder>
    <deliveryOrderCode>{$code}</deliveryOrderCode>
    <deliveryOrderId>{$code}</deliveryOrderId>
    <warehouseCode>shenyi_xdb</warehouseCode>
    <orderType>JYCK</orderType>
    <outBizCode>{$code}</outBizCode>
    <orderConfirmTime>2019-12-09 14:50:00</orderConfirmTime>
  </deliveryOrder>
  <packages>
    <package>
      <logisticsCode>SF</logisticsCode>
      <expressCode>{$trackingNo}</expressCode>
      <weight>315.0000</weight>
      <extendProps>
        <postage>0.0000</postage>
      </extendProps>
      <items>
        <item>
          <itemCode>FCBH001</itemCode>
          <quantity>2</quantity>
        </item>
      </items>
    </package>
  </packages>
  <orderLines>
    <orderLine>
      <itemCode>FCBH001</itemCode>
      <inventoryType>ZP</inventoryType>
      <actualQty>2</actualQty>
      <batchs>
        <batch>
          <batchCode>SAJH013-001</batchCode>
          <expireDate>2021-08-07 00:00:00</expireDate>
          <actualQty>2</actualQty>
          <inventoryType>ZP</inventoryType>
        </batch>
      </batchs>
    </orderLine>

    <!-- <orderLine>
      <itemCode>FCBH001</itemCode>
      <inventoryType>ZP</inventoryType>
      <actualQty>2</actualQty>
      <batchs>
        <batch>
          <batchCode>SAJH013-001</batchCode>
          <expireDate>2021-08-07 00:00:00</expireDate>
          <actualQty>2</actualQty>
          <inventoryType>ZP</inventoryType>
        </batch>
      </batchs>
    </orderLine> -->

  </orderLines>
</request>

XML;
    return $body;
  }

  /**
   * @param $url
   * @param $data
   * @param string $request
   * @return return
   */
  public function httpCurl($url, $data, $requestType = 'get') {
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

$confirm = new DeliveryConfirm();
$result = $confirm->delivery_confirm();
