<?php

namespace Call;

include "Conf/conf.class.php";
use Call\Conf\Config;
use Call\HttpFoundation\JsonResponse;
use Call\Enum\Enum;
use Call\Object\DeliveryOrder;
use Call\Object\EntryOrder;
use Call\Object\OutStockOrder;
use Call\Object\RefundOrder;
use Call\Object\Warehouse;

$AppKernel = new Config();
$AppKernel->loadConf('Enum/Enum.php');
$AppKernel->loadConf('HttpFoundation/JsonResponse.php');
$AppKernel->loadConf('HttpFoundation/Response.php');
$AppKernel->loadConf('Parameters.php');
$AppKernel->loadConf('Web/AppMethod.php');
$AppKernel->loadConf('Base/Project.php');
# 获取 method 值
if (php_sapi_name() == 'cli') {
    $transfersType = Enum::getTransferType($argv[1]);
    $method = $argv[2];
    $mode = 'cli';
} else {
    $transfersType = $_GET['transferType'];
    $method = $_GET['method'];
    $mode = 'webPage';
}
switch ($method) {
    case Enum::DELIVERY:
        $AppKernel->loadConf("Object/DeliveryOrder.php");
        $object = new DeliveryOrder();
        break;

    case Enum::IN_STOCK:
        $AppKernel->loadConf("Object/EntryOrder.php");
        $object = new EntryOrder();
        break;

    case Enum::OUT_STOCK:
        $AppKernel->loadConf("Object/OutStockOrder.php");
        $object = new OutStockOrder();
        break;

    case Enum::REFUND:
        $AppKernel->loadConf("Object/RefundOrder.php");
        $object = new RefundOrder();
        break;

	case Enum::WAREHOUSE:
		$AppKernel->loadConf("Object/Warehouse.php");
		$object = new Warehouse();
		$res = $object->getWarehouse();
		return new JsonResponse($res);

    default:
        throw new \Exception("无效的请求");
}
$res = $object->confirm($method, $transfersType, $mode);
// 返回结果是数组，用JsonResponse
return new JsonResponse($res);
