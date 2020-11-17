<?php

namespace Call\Post;

include_once DIR_PATH."/Request/RequestClientBase.php";
include_once DIR_PATH."/Request/ApiRequestInterface.php";
include_once DIR_PATH."/Request/RefundConfirmRequest.php";
include_once DIR_PATH."/Request/TopClient.php";

use Call\Request\ApiRequestInterface;
use Call\Request\RefundConfirmRequest;
use Call\Request\RequestClientBase;
use Call\Request\TopClient;

/**
 * 出库单确认数据
 * Class StockOutPost
 * @package Call\Post
 */
class RefundPost extends RequestClientBase implements ApiRequestInterface
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
        $returnOrder = $this->returnOrder();
        $senderInfo = $this->setSenderInfo();
        $orderLines = $this->orderLines();
        $req = new RefundConfirmRequest();
        $req->setReturnOrder($returnOrder);
        $req->setSenderInfo($senderInfo);
        $req->setOrderLines($orderLines);
        return $req->getApiParas();
    }

    /**
     * 出库单主体部分
     * @return array
     */
    protected function returnOrder(): array
    {
        return [
            'returnOrderCode' => $this->postData['returnOrder']['returnOrderCode'],
            'returnOrderId'   => $this->postData['returnOrder']['returnOrderId'],
            'warehouseCode'   => $this->postData['returnOrder']['warehouseCode'],
            'orderType'       => $this->postData['returnOrder']['orderType'],
            'expressCode'     => $this->postData['returnOrder']['expressCode'],
        ];
    }

    /**
     * @return array
     */
    protected function setSenderInfo()
    {
        return [
            'name' => $this->postData['senderInfo']['name'],
            'mobile' => $this->postData['senderInfo']['mobile'],
            'province' => $this->postData['senderInfo']['province'],
            'city' => $this->postData['senderInfo']['city'],
            'detailAddress' => $this->postData['senderInfo']['detailAddress'],
        ];
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
