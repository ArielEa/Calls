<?php

namespace Call\Request;

/**
 * 退货入库单确认接口
 * Class RefundConfirmRequest
 * @package Call\Request
 */
class RefundConfirmRequest
{
    private $returnOrder;

    private $senderInfo;

    private $orderLines;

    private $extendProps;

    private $apiParas = [];

    /**
     * @return mixed
     */
    public function getReturnOrder()
    {
        return $this->returnOrder;
    }

    /**
     * @param mixed $returnOrder
     */
    public function setReturnOrder($returnOrder): void
    {
        $this->returnOrder = $returnOrder;
        $this->apiParas['returnOrder'] = $returnOrder;
    }

    /**
     * @return mixed
     */
    public function getSenderInfo()
    {
        return $this->senderInfo;
    }

    /**
     * @param mixed $senderInfo
     */
    public function setSenderInfo($senderInfo): void
    {
        $this->senderInfo = $senderInfo;
        $this->apiParas['senderInfo'] = $senderInfo;
    }

    /**
     * @return mixed
     */
    public function getOrderLines()
    {
        return $this->orderLines;
    }

    /**
     * @param mixed $orderLines
     */
    public function setOrderLines($orderLines): void
    {
        $this->orderLines = $orderLines;
        $this->apiParas['orderLines'] = $orderLines;
    }

    /**
     * @return mixed
     */
    public function getExtendProps()
    {
        return $this->extendProps;
    }

    /**
     * @param mixed $extendProps
     */
    public function setExtendProps($extendProps): void
    {
        $this->extendProps = $extendProps;
        $this->apiParas['extendProps'] = $extendProps;
    }

    /**
     * @return array
     */
    public function getApiParas(): array
    {
        return $this->apiParas;
    }

    /**
     * - [ 接口名 ]
     * @return string
     */
    public function getApiMethodName(): string
    {
        return "taobao.qimen.returnorder.confirm";
    }
}
