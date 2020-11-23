<?php

namespace Call\Object;

use Call\Enum\Enum;

/**
 * 以文件形式存储
 * Class Warehouse
 * @package Call\Object
 */
class Warehouse
{
    // 文件
    protected static $fileDir = DIR_PATH."/Web/Files/warehouse.log";
    // 地址
    protected $url;
    // 路由
    protected $warehouseRoute;

    protected $token;

    protected $tokenPrefix;

    /**
     * 获取路由配置信息
     */
    public function getParameters()
    {
        $methodFile = Enum::getPara();
        ## todo:: 直接拿取配置参数，不解析
        $parameters = matchParameters($methodFile);
        $this->url = $parameters['remoteUrl'].$parameters['warehouseRoute'];
        $this->token = $parameters['token'];
        $this->tokenPrefix = $parameters['tokenPrefix'];
    }

    /**
     * 获取仓库信息
     * @return array
     */
    public function getWarehouse()
    {
        $this->getParameters();
        // 这里是针对oms的查询
        $url = "curl {$this->url} -X GET -H '{$this->tokenPrefix}: {$this->token}'";

        $res = exec($url);
        if (!$res) {
            return ['code' => 50, 'flag' => 'failure', 'msg' => '查询失败'];
        }
        $result = json_decode($res, true);

        if ($result['errCode'] != 0) {
            return ['code' => 50, 'flag' => 'failure', 'msg' => $result['errMsg']];
        }
        $warehouse = [];
        // 获取需要的仓库信息
        foreach ( $result['payload'] as $key => $value ) {
            $warehouse[] = [
                'warehouse_name' => $value['warehouse_name'],
                'warehouse_code' => $value['warehouse_bn']
            ];
        }
        try {
            $logFile = fopen(self::$fileDir, "w");
            fwrite($logFile, json_encode($warehouse, JSON_UNESCAPED_UNICODE));
            fclose($logFile);
        }catch ( \Exception $e ) {
            return  ['code' => 50, 'flag' => 'failure', 'msg' => "写入失败: {$e->getMessage()}"];
        }
        return  ['code' => 0, 'flag' => 'success', 'msg' => "写入成功"];
    }
}
