<?php

namespace Call\Request;

/**
 * 入库单确认接口
 * Class EntryOrderConfirmRequest
 * @package Call\Request
 */
class EntryOrderConfirmRequest
{
    private $entryOrder;

    private $orderLines;

    private $extendProps;

    private $apiParas = [];

    /**
     * @return mixed
     */
    public function getEntryOrder()
    {
        return $this->entryOrder;
    }

    /**
     * @param mixed $entryOrder
     */
    public function setEntryOrder($entryOrder): void
    {
        $this->entryOrder = $entryOrder;
        $this->apiParas['entryOrder'] = $entryOrder;
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
     * - [ 接口名称 ]
     * @return string
     */
    public function getApiMethodName(): string
    {
        return "taobao.qimen.entryorder.confirm";
    }
}
