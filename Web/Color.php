<?php
/**
 * 颜色
 * 命令行输出颜色设定
 */
namespace Call\Conf;

if (!function_exists('colorize')) {
    function colorize($text, $status) {
        switch($status) {
            case "SUCCESS":
                $out = "\033[32m"; //Green background
                break;
            case "FAILURE":
                $out = "\033[33m"; //Red background
                break;
            case "WARNING":
                $out = "\033[31m"; //Yellow background
                break;
            case "NOTE":
                $out = "\033[34m"; //Blue background
                break;
            default:
                $text = "无效的识别类型: {$status}";
                $out = "\033[31m";
                break;
        }
        $returnData = PHP_EOL.$out."=====================================".PHP_EOL.PHP_EOL;
        $returnData .= $text.PHP_EOL.PHP_EOL;
        $returnData .= "=====================================".PHP_EOL.PHP_EOL;
        return chr(27) . $returnData . chr(27) . "[0m";
    }
}
