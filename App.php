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

$AppKernel = new Config();
$AppKernel->loadConf('Enum/Enum.php');
$AppKernel->loadConf('HttpFoundation/JsonResponse.php');
$AppKernel->loadConf('HttpFoundation/Response.php');
$AppKernel->loadConf('Parameters.php');
$AppKernel->loadConf('Web/AppMethod.php');
$AppKernel->loadConf('Base/Project.php');
# 获取 method 值
$method = php_sapi_name() == 'cli' ? $argv[1] : $_GET['method'];

switch ($method) {
    case Enum::$delivery:
        $AppKernel->loadConf("Object/DeliveryOrder.php");
        $object = new DeliveryOrder();
        break;

    case Enum::$inStock:
        $AppKernel->loadConf("Object/EntryOrder.php");
        $object = new EntryOrder();
        break;

    case Enum::$outStock:
        $AppKernel->loadConf("Object/OutStockOrder.php");
        $object = new OutStockOrder();
        break;

    case Enum::$refund:
        $AppKernel->loadConf("Object/RefundOrder.php");
        $object = new RefundOrder();
        break;

    default:
        throw new \Exception("Invalid Status");
}
$res = $object->confirm($method);
// 返回结果是数组，用JsonResponse
return new JsonResponse($res);