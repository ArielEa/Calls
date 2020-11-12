<?php

namespace Call\Post;

include_once DIR_PATH."/Request/RequestClientBase.php";
include_once DIR_PATH."/Request/ApiRequestInterface.php";
include_once DIR_PATH."/Request/EntryOrderConfirmRequest.php";
include_once DIR_PATH."/Request/TopClient.php";

use Call\Request\ApiRequestInterface;
use Call\Request\EntryOrderConfirmRequest;
use Call\Request\RequestClientBase;
use Call\Request\TopClient;

/**
 * 入库单确认
 * Class EntryOrderPost
 * @package Call\Post
 */
class EntryOrderPost extends RequestClientBase implements ApiRequestInterface
{
    protected $postData = [];

    /**
     * 入库单任务执行
     * @param $url
     * @param $postData
     * @param string $postType
     * @param string $status
     * @return array
     */
    public function request($url, $postData, $postType = 'post', $status = 'diy')
    {
        $this->postData = $postData;
        $entryOrder = $this->entryOrder();
        $orderLiens = $this->orderLines();
        $req = new EntryOrderConfirmRequest();
        $req->setEntryOrder($entryOrder);
        $req->setOrderLines($orderLiens);
        if ($status == 'diy') {
            return $req->getApiParas();
        } else {
//            $request = new TopClient();
            return [];
        }
    }

    /**
     * 主体部分
     */
    protected function entryOrder()
    {
        return [
            'entryOrderCode' => $this->postData['entryOrder']['entryOrderCode'],
            'entryOrderId'   => $this->postData['entryOrder']['entryOrderCode'],
            'warehouseCode'  => $this->postData['entryOrder']['warehouseCode'],
            'entryOrderType' => $this->postData['entryOrder']['entryOrderType'],
            'outBizCode'     => $this->postData['entryOrder']['entryOrderCode'],
            'confirmType'    => 1,
            'status'         => 'FULFILLED',
            'remark'         => '',
        ];
    }

    /**
     * 商品部分
     */
    protected function orderLines()
    {
        $orderLines = [];
        $products = $this->postData['orderLines'];
        foreach ( $products as $key => $val ) {
            $batch = [
                'batchCode'     => '',
                'productDate'   => '',
                'expireDate'    => '',
                'actualQty'     => '',
                'inventoryType' => '',
            ];
            $orderLines[] = [
                'itemId'        => $val['itemCode'],
                'itemCode'      => $val['itemCode'],
                'inventoryType' => $val['inventory'],
                'actualQty'     => $val['actualQty'],
                'batchs'        => ['batch' => $batch]
            ];
        }
        return $orderLines;
    }
}
