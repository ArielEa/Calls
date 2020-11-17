<?php
/**
 * 入口文件，判断状态是否正确
 * php_sapi_name php操作模式
 * cli 模式和 php-fpm 模式下接收值的方式不同
 */
namespace Call\Conf;

use Call\HttpFoundation\JsonResponse;
use Call\HttpFoundation\Response;

define("DIR_PATH", __DIR__."/../");

include_once "Color.php";

$methodArr = ['delivery', 'inStock', 'outStock', 'refund'];

if (php_sapi_name() == 'cli') {
    // 命令行模式
    $argv = $GLOBALS['argv'];
    $method = isset($argv[1]) ? trim($argv[1]) : '';
    if ($method == '-h') {
        $message = "当前可选择的状态\n发货单     : delivery\n入库单     : inStock\n出库单     : outStock\n退货入库单 : refund";
        return new Response(colorize($message, 'WARNING'));
    }
    if (!$method) {
        return new Response(colorize("请输入状态",'FAILURE'));
    }
    if (!in_array($method, $methodArr)) {
        return new Response(colorize('', $method));
    }
} else {
    // 浏览器模式
    $method = $_GET['method'];
    if (!in_array($method, $methodArr)) {
        $str =  "<span style='color: green'>============================</span><br/>";
        $str .= "
<div style='background: green; width: 252px; height: auto;'>
    <div style='margin-left: 20px; padding: 5px 0 5px 0; font-weight: bold;font-size: 14px'>
        <p>状态错误,当前可选状态有：</p>
        <p>发货单 : delivery</p>
        <p>入库单 : inStock</p>
        <p>出库单 : outStock</p>
        <p>退货入库单 : refund</p>
    </div>
</div>";
        $str .= "<span style='color: green'>============================</span>";
        return new Response($str);
    }
}
