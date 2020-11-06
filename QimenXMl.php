<?php
/* 奇门回传xml部分 */

namespace Call;

trait QimenXML
{
	public function qimenXML()
	{
	    $time = date("Y-m-d H:i:s", time());

		$body = <<<XML
<?xml version="1.0" encoding="utf-8"?>

<request>
  <deliveryOrder>
    <deliveryOrderCode>201027220000038</deliveryOrderCode>
    <deliveryOrderId>201027220000038</deliveryOrderId>
    <warehouseCode>shenyi_xdb</warehouseCode>
    <orderConfirmTime>{$time}</orderConfirmTime>
    <orderType>JYCK</orderType>
    <outBizCode>200916829679720 - SFGD</outBizCode>
  </deliveryOrder>
  <packages>
    <package>
      <logisticsCode>SFTH</logisticsCode>
      <expressCode>SF104073474812</expressCode>
      <weight>315.0000</weight>
      <extendProps>
        <postage>0.0000</postage>
      </extendProps>
      <items>
        <item>
          <itemCode>SAJH047</itemCode>
          <quantity>2</quantity>
        </item>
      </items>
    </package>
    <package>
      <logisticsCode>SFTH</logisticsCode>
      <expressCode>SF204073471002</expressCode>
      <weight>0.0000</weight>
      <extendProps>
        <postage>0.0000</postage>
      </extendProps>
      <items>
        <item>
          <itemCode>muti_goods</itemCode>
          <quantity>1</quantity>
        </item>
      </items>
    </package>
    <package>
      <logisticsCode>SFTH</logisticsCode>
      <expressCode>SF204073421002</expressCode>
      <weight>0.0000</weight>
      <extendProps>
        <postage>0.0000</postage>
      </extendProps>
      <items>
        <item>
          <itemCode>muti_goods</itemCode>
          <quantity>1</quantity>
        </item>
      </items>
    </package>
  </packages>
  <orderLines>
    <orderLine>
      <itemCode>SAJH047</itemCode>
      <inventoryType>ZP</inventoryType>
      <actualQty>2</actualQty>
      <batchs>
        <batch>
          <batchCode>L128</batchCode>
          <expireDate>2021-08-07 00:00:00</expireDate>
          <actualQty>2</actualQty>
          <inventoryType>ZP</inventoryType>
          <productDate/>
        </batch>
      </batchs>
    </orderLine>
  </orderLines>
</request>

XML;
    return $body;
	}
}
