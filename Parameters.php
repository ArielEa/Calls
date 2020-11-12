<?php
/**
 * - [ 处理配置文件 ]
 * @author Ariel.
 */
include_once DIR_PATH."Conf/Spyc.php";

if (!function_exists('getParameters')) {
    /**
     * -【获取配置参数设置】
     * @param $key
     * @param $fileName
     * @return mixed|string
     * @throws Exception
     */
    function getParameters($key, $fileName)
    {
        $parameters = matchParameters($fileName, $key);

        if(empty($parameters)) {
            throw new \Exception("没有匹配的配置参数");
        }
        if (!isset($parameters[$key])) {
            throw new \Exception("没有发现{$key}的配置参数");
        }
        return $parameters[$key];
    }
}

if (!function_exists('matchParameters')) {
    /**
     * - 【获取全部配置参数】
     * @param $fileName
     * @param $key
     * @return mixed|string
     */
    function matchParameters($fileName, $key = '')
    {
        $yaml = \Spyc::YAMLLoad($fileName);
        if (!in_array($key, ['inStock', 'outStock', 'delivery', 'refund'])) {
            return isset( $yaml['Parameters'] ) ? $yaml['Parameters'] : '' ;
        } else {
            return $yaml;
        }
    }
}
