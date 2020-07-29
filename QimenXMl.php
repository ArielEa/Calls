<?php
/**
 * 奇门回传xml部分
 */
trait QimenXML
{
	public function qimenXML()
	{

    $time = date("Y-m-d H:i:s", time());

		$body = <<<XML
<?xml version="1.0" encoding="utf-8"?>

<request>
  <deliveryOrder>
    <deliveryOrderCode>200728236608483</deliveryOrderCode>
    <deliveryOrderId>200728236608483</deliveryOrderId>
    <warehouseCode>shenyi_xdb</warehouseCode>
    <orderConfirmTime>{$time}</orderConfirmTime>
    <orderType>JYCK</orderType>
    <outBizCode>200728333834886 - </outBizCode>
  </deliveryOrder>
  <packages>
    <package>
      <logisticsCode>SF</logisticsCode>
      <expressCode>SF2153763122</expressCode>
      <weight>315.0000</weight>
      <extendProps>
        <postage>0.0000</postage>
      </extendProps>
      <items>
        <item>
          <itemCode>FCBH001</itemCode>
          <quantity>6</quantity>
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
          <batchCode>L128</batchCode>
          <expireDate>2021-08-07 00:00:00</expireDate>
          <actualQty>1</actualQty>
          <inventoryType>ZP</inventoryType>
        </batch>
      </batchs>
    </orderLine>
  </orderLines>
</request>

XML;
    return $body;
	}
}