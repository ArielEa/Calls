<?php

namespace Call\Conf;

/**
 * Class Config
 * @package Call\Conf
 */
class Config
{
    protected static $config;

    /**
     * 加载配置
     * @param $confFile
     * @throws \Exception
     */
    public function loadConf($confFile){
        if (is_file($confFile)){
             self::$config = include_once $confFile;
        }
    }

    /**
     * 获取配置文件
     * @param $name
     * @return mixed|string
     */
    public function getConf($name){
        if(isset(self::$config[$name])){
            return self::$config[$name];
        }else{
            return " config $name is undefined ";
        }
    }

}
