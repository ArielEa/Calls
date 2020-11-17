<?php

namespace Call\Post;

include_once DIR_PATH."/Request/RequestClientBase.php";
include_once DIR_PATH."/Request/ApiRequestInterface.php";
include_once DIR_PATH."/Request/StockOutConfirmRequest.php";
include_once DIR_PATH."/Request/TopClient.php";

use Call\Request\ApiRequestInterface;
use Call\Request\StockOutConfirmRequest;
use Call\Request\RequestClientBase;
use Call\Request\TopClient;

/**
 * 出库单确认数据
 * Class StockOutPost
 * @package Call\Post
 */
class StockOutPost extends RequestClientBase implements ApiRequestInterface
{
    protected $postData = [];

    /**
     * @param $url
     * @param $postData
     * @param string $postType
     * @param string $status
     * @return array
     * @throws \Exception
     */
    public function request($url, $postData, $postType = 'post', $status = 'diy')
    {
        $this->postData = $postData;
        $deliveryOrder = $this->deliveryOrder();
        $packages = $this->packages();
        $orderLines = $this->orderLines();
        $req = new StockOutConfirmRequest();
        $req->setDeliveryOrder($deliveryOrder);
        $req->setPackages($packages);
        $req->setOrderLines($orderLines);
        return $req->getApiParas();
    }

    /**
     * 出库单主体部分
     * @return array
     */
    protected function deliveryOrder(): array
    {
        return [
            'deliveryOrderCode' => $this->postData['deliveryOrder']['deliveryOrderCode'],
            'deliveryOrderId'   => $this->postData['deliveryOrder']['deliveryOrderId'],
            'warehouseCode'     => $this->postData['deliveryOrder']['warehouseCode'],
            'ownerCode'         => $this->postData['deliveryOrder']['ownerCode'],
            'outBizCode'        => $this->postData['deliveryOrder']['outBizCode'],
            'orderType'         => $this->postData['deliveryOrder']['orderType'],
            'status'            => $this->postData['deliveryOrder']['status'],
            'orderConfirmTime'  => $this->postData['deliveryOrder']['orderConfirmTime'],
            'logisticsCode'     => $this->postData['deliveryOrder']['logisticsCode'],
            'expressCode'       => $this->postData['deliveryOrder']['expressCode']
        ];
    }

    protected function packages(): array
    {
        $packages = $this->postData['packages'];
        $cPackages = [];
        foreach ($packages as $key => $value) {
            $cPackageKey = 'package_'.$key;
            $cPackages[$cPackageKey] = [
                'items' => $this->setItem($value['items'])
            ];
        }
        return $cPackages;
    }

    /**
     * PackageItems
     * @param $items
     * @return array
     */
    protected function setItem($items)
    {
        $cItems = [];
        foreach ($items as $k => $v) {
            $combineKey = 'item_'.$k;
            $cItems[$combineKey] = [
                'itemCode'  => $v['itemCode'],
                'quantity'  => $v['quantity']
            ];
        }
        return $cItems;
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
