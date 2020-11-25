<?php

namespace Call\Enum;

abstract class Enum
{
    const  DELIVERY = 'delivery';

    const IN_STOCK = 'inStock';

    const OUT_STOCK = 'outStock';

    const REFUND = 'refund';

    const WAREHOUSE = 'warehouse';

    private static $paraFile = 'Conf/Parameters.yaml';

    ## 文件处理部分
    private static $parameters = [
        'inStock'   => 'ArrRequest/InStockConfirmArr.yaml',
        'outStock'  => 'ArrRequest/OutStockConfirmArr.yaml',
        'delivery'  => 'ArrRequest/DeliveryConfirmArr.yaml',
        'refund'    => 'ArrRequest/RefundConfirmArr.yaml'
    ];

    /**
     * 商品正残属性
     * @var string[]
     */
    public static $inventory = [
        '正品' => 'ZP',
        '残品' => 'CC'
    ];

    ## 传输类型
    const Excel = 'Excel'; // excel 文件

    const Help = 'Help'; // 帮助类型

    const Parameters = "Parameters"; // 配置读取类型

    /**
     * 枚举 - 传输类型
     * @var string[]
     */
    private static $transferType = [
        '-e' => self::Excel,
        '-h' => self::Help,
        '-p' => self::Parameters
    ];

    /**
     * 需要的文件传递类型
     * @param $key
     * @return string
     */
    public static function getTransferType($key): string
    {
        if (isset(self::$transferType[$key])) {
            return self::$transferType[$key];
        } else {
            return '';
        }
    }

    /**
     * 返回对应的文件
     * @param string $key
     * @return string
     */
    public static function getPara($key = '') : string
    {
        if (!$key) return self::$paraFile;

        if (isset(self::$parameters[$key])) {
            return self::$parameters[$key];
        } else {
            return self::$paraFile;
        }
    }
}
