<?php

namespace MethodRequest\Conf;

class Config
{
    protected static $config;

    // 加载配置文件
    function loadConf($confFile){
        if (is_file($confFile)){
             self::$config = include_once $confFile;
        }
    }

    function getConf($name){
        if(isset(self::$config[$name])){
            return self::$config[$name];
        }else{
            return " config $name is undefined ";
        }
    }

}
