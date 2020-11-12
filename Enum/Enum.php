<?php

namespace Call\Enum;

abstract class Enum
{
    public static $delivery = 'delivery';

    public static $inStock = 'inStock';

    public static $outStock = 'outStock';

    public static $refund = 'refund';

    private static $paraFile = 'Conf/Parameters.yaml';

    ## 文件处理部分
    private static $parameters = [
        'inStock'   => 'ArrRequest/InStockConfirmArr.yaml',
        'outStock'  => 'ArrRequest/OutStockConfirmArr.yaml',
        'delivery'  => 'ArrRequest/DeliveryConfirmArr.yaml',
        'refund'    => 'ArrRequest/RefundConfirmArr.yaml'
    ];

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
