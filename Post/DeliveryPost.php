<?php

namespace Call\Post;


include_once DIR_PATH."/Request/RequestClientBase.php";
include_once DIR_PATH."/Request/ApiRequestInterface.php";
include_once DIR_PATH."/Request/DeliveryConfirmRequest.php";

use Call\Request\ApiRequestInterface;
use Call\Request\DeliveryConfirmRequest;
use Call\Request\RequestClientBase;

/**
 * Class DeliveryPost
 * @package Call\Post
 */
class DeliveryPost extends RequestClientBase implements ApiRequestInterface
{
    protected $postData = []; // 发货单基础信息

    public function request($url, $postData, $postType = 'post', $status = 'diy')
    {
        $this->postData = $postData;
        $deliveryOrder  = $this->deliveryOrder();
        $packages       = $this->packages();
        $orderLines     = $this->orderLines();
        $req = new DeliveryConfirmRequest();
        $req->setDeliveryOrder($deliveryOrder);
        $req->setPackages($packages);
        $req->setOrderLines($orderLines);
        return $req->getApiParas();
    }

    /**
     * 发货
     * @return array
     */
    protected function deliveryOrder(): array
    {
        $deliveryOrder = $this->postData['deliveryOrder'];
        return [
            'deliveryOrderCode'	=> $deliveryOrder['deliveryOrderCode'],
            'deliveryOrderId'	=> $deliveryOrder['deliveryOrderId'],
            'warehouseCode'	    => $deliveryOrder['warehouseCode'],
            'orderConfirmTime'	=> date("Y-m-d H:i:s", time()),
            'orderType'	        => $deliveryOrder['orderType'],
            'outBizCode'	    => $deliveryOrder['outBizCode']
        ];
    }

    /**
     * 物流包裹信息
     * @return array
     */
    protected function packages(): array
    {
        $packages = $this->postData['packages'];

        if (empty($packages)) return [];

        $combinePackages = [];
        foreach ($packages as $key => $value) {
            $pacKey = "package_{$key}";
            $combinePackages[$pacKey] = [
                'logisticsCode' => $value['logisticsCode'],
                'expressCode'   => $value['expressCode'],
                'weight'        => $value['weight'],
                'extendProps'   => [
                    'postage'   => $value['extendProps']['postage']
                ],
                'items' => $this->packageItems($value['items'])
            ];
        }
        return $combinePackages;
    }

    /**
     * Packages Items --> Logistics Info
     * @param $items
     * @return array
     */
    protected function packageItems($items)
    {
        if (empty($items)) return [];

        $packageItem = [];
        foreach ($items as $k => $v) {
            $combineKey = 'item_'.$k;
            $packageItem[$combineKey] = [
                'itemCode'  => $v['itemCode'],
                'quantity'  => $v['quantity']
            ];
        }
        return $packageItem;
    }

    /**
     * 主体发货商品信息
     * @return array
     * @throws \Exception
     */
    protected function orderLines(): array
    {
        $orderLines = $this->postData['orderLines'];

        if (empty( $orderLines )) {
            throw new \Exception("Invalid OrderLines");
        }
        $combineLines = [];
        foreach ($orderLines as $key => $val) {
            $row = [
                'itemCode'      => $val['itemCode'],
                'inventoryType' => $val['inventoryType'],
                'actualQty'     => $val['actualQty'],
                'planQty'       => $val['actualQty']
            ];
            if (isset( $val['batchs'] )) {
                $row['batchs'] = $this->setBatchs($val['batchs']);
            }
            $combineLines[] = $row;
        }
        return $combineLines;
    }

    /**
     * 设置批次信息
     * @param $batchRows
     * @return array
     */
    protected function setBatchs($batchRows): array
    {
        $data = [];
        foreach ( $batchRows as $key => $value ) {
            $newKey = "batch_{$key}";
            $data[$newKey] = [
                "batchCode" => $value['batchCode'],
                "productDate" => $value['productDate'],
                "expireDate" => $value['expireDate'],
                "actualQty" => $value['actualQty'],
                "inventoryType" => $value['inventoryType']
            ];
        }
        return $data;
    }
}
