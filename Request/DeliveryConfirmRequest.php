<?php

namespace Call\Request;

/**
 * 发货单确认发货接口
 * Class DeliveryConfirmRequest
 * @package Call\Request
 */
class DeliveryConfirmRequest
{
    private $deliveryOrder;

    private $packages;

    private $orderLines;

    private $extendProps;

    private $apiParas = [];

    /**
     * @return mixed
     */
    public function getDeliveryOrder()
    {
        return $this->deliveryOrder;
    }

    /**
     * @param mixed $deliveryOrder
     */
    public function setDeliveryOrder($deliveryOrder): void
    {
        $this->deliveryOrder = $deliveryOrder;
        $this->apiParas['deliveryOrder'] = $deliveryOrder;
    }

    /**
     * @return mixed
     */
    public function getPackages()
    {
        return $this->packages;
    }

    /**
     * @param mixed $packages
     */
    public function setPackages($packages): void
    {
        $this->packages = $packages;
        $this->apiParas['packages'] = $packages;
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
     * @return string
     */
    public function getApiMethodName(): string
    {
        return "taobao.qimen.deliveryorder.confirm";
    }
}
