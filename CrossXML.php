<?php

namespace Call;

trait CrossXML
{
	public function CrossXML()
	{
		$body = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<request>
  <deliveryOrder> 
    <deliveryOrderCode>T0202006289454070987</deliveryOrderCode>
    <deliveryOrderId>T0202006289454070987</deliveryOrderId>
    <warehouseCode>WH42</warehouseCode>
    <outBizCode>DO=T0202003126143599418 stock out confirm</outBizCode>
    <confirmType>0</confirmType>
    <orderType>JYCK</orderType>
  </deliveryOrder>
  <packages>
    <package>
      <logisticsCode>SF</logisticsCode> 
      <logisticsName>SF</logisticsName>
      <expressCode>SF214151283</expressCode>
      <weight>1.0</weight>
      <items>
        <item>
          <itemCode>FCBH001</itemCode>
          <itemId>clientsku-92fb0723-780f-4973-bcfb-8b3843d07d01</itemId>
          <quantity>2</quantity>
        </item>
      </items>
    </package>
  </packages>
  <orderLines>
    <orderLine>
      <itemCode>FCBH001</itemCode>
      <itemId>648038944691</itemId>
      <inventoryType>ZP</inventoryType>
      <planQty>2</planQty>
      <actualQty>2</actualQty>
      <batchCode>1840901</batchCode>
    </orderLine>
  </orderLines>
</request>
XML;
    return $body;
	}
}