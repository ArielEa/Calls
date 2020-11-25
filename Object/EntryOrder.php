<?php
namespace Call\Object;

include_once DIR_PATH."/HttpCurl/HttpCurl.php";
include_once DIR_PATH."Parameters.php";
include_once DIR_PATH."Enum/Enum.php";
include_once DIR_PATH."Post/EntryOrderPost.php";
include_once DIR_PATH."Base/Project.php";
include_once DIR_PATH."Object/ReadExcel.php";
include_once DIR_PATH."Object/Warehouse.php";

use Call\HttpCurl\HttpCurl;
use Call\Enum\Enum;
use Call\Post\EntryOrderPost;

/**
 * - [ 入库单模版 ]
 * Class EntryOrder
 * @package MethodRequest
 */
class EntryOrder extends HttpCurl
{
    // method 入库单确认接口
    protected static $method = 'entryorder.confirm';

    protected static $inStockFile = DIR_PATH."/ExcelFiles/inStockConfirm.xlsx";

    // 暂时默认，没有其他平台
    protected static $platform = 'qimen';

    // 处理方式, 平台或者手动 (platform/diy)
    protected static $handleState = 'diy';

    protected $transferType;

    protected $mode;

    // 入库类型
    protected $entryOrderType = [
        '直接入库' => 'QTRK',
        '调拨入库' => 'DBRK',
        '采购入库' => 'CGRK',
    ];

    /**
     * @param $method
     * @return mixed|string
     * @throws \Exception
     */
    protected function requestData($method)
    {
        $paraFile = Enum::getPara($method);
        return getParameters($method, $paraFile);
    }

    /**
     * @param $method - 单据类型
     * @param $transferType - 当前操作模式， 文件还是配置模式
     * @param $mode - 当前请求模式
     * @return array
     * @throws \Exception
     */
    public function confirm($method, $transferType, $mode) : array
    {
        $this->transferType = $transferType;
        $this->mode = $mode;
        // 前置配置信息
        $this->combineUrl(self::$method);
        try {
            if ($transferType == Enum::Parameters) {
                // 配置模式
                $xmlData = $this->requestData($method); // 拿取配置中的信息
                $req = $this->postData($xmlData, new EntryOrderPost());
            } else if ($transferType == Enum::Excel) {
                if ($mode == 'cli') {
                    $req = $this->readExcel(self::$inStockFile);
                } else {
                    $req = $this->webExcel();
                }
            }else {
                throw new \Exception("无效的执行命令");
            }
            $resp = [];
            foreach ( $req as $value ) {
                $XML = convertXml($value);
                $resXml = $this->sendRequest($XML, 'post');
                $resData = $this->convertXml($resXml, $value['entryOrder']['entryOrderCode']);
                $resp[] = $resData;
            }
        } catch (\Exception $e) {
            return [
                ['code' => 500, 'flag' => 'failure', 'msg' => $e->getMessage(), 'response_code' => null]
            ];
        }
        return $resp;
    }

    /**
     * @param $xml
     * @param $code
     * @return array
     * @throws \Exception
     */
    protected function convertXml($xml, $code): array
    {
        $parseData =  parseXml($xml);;
        $parseData['response_code'] = $code;
        return $parseData;
    }

    /**
     * 处理从页面过来的excel文件，并调用readExcel方法读取
     * @return array
     * @throws \Exception
     */
    protected function webExcel()
    {
        $excelFile = $_FILES['excelFile'];
        if (!$excelFile) throw new \Exception("请上传文件");
        $fileName = $excelFile['name']; // 文件名
        $fileTmpName = $excelFile['tmp_name']; // 文件临时目录
        return $this->readExcel($fileTmpName);
    }

    /**
     * @return array
     * @param $fileName
     * @throws \Exception
     */
    protected function readExcel($fileName): array
    {
        $res = (new \ReadExcel())->readExcelFile($fileName);
        if (!$res) {
            throw new \Exception("无效的Excel文件");
        }
        return $this->handleEntryOrder($res);
    }

    /**
     * 处理excel读取出来的数据
     * @param $rows
     * @return array
     * @throws \Exception
     */
    protected function handleEntryOrder($rows)
    {
        $warehouseLog = file_get_contents(DIR_PATH."Web/Files/warehouse.log");
        $warehouse = json_decode($warehouseLog, true);
        if (empty($warehouse)) {
           throw new \Exception("请重新更新下仓库信息: ./console -e warehouse");
        }
        $data = [];
        foreach ( $rows as $key => $value ) {
            $data[$value[0]][] = $value;
        }
        $entryData = [];
        foreach ( $data as $key => $value ) {
            $entryMain = $value[0];
            $entry = [
                'entryOrder' => [
                    'entryOrderCode' => $entryMain[0],
                    'warehouseCode'  => isset($warehouse[$entryMain[2]]) ? $warehouse[$entryMain[2]]['warehouse_code'] : '',
                    'entryOrderType' => $this->entryOrderType[$entryMain[1]] ?? '',
                    'entryOrderId'   => $entryMain[0],
                    'outBizCode'     => $entryMain[0],
                    'confirmType'    => 1,
                    'status'         => 'FULFILLED',
                    'remark'         => '',
                ],
                'orderLines' => $this->handleItem($value)
            ];
            $entryData[] = $entry;
        }
        return $entryData;
    }

    /**
     * 处理自商品信息
     * @param $items
     * @return array
     */
    protected function handleItem($items)
    {
        $orderLines = [];
        $inventory = Enum::$inventory;
        foreach ( $items as $k => $v ) {
            $orderLines[] = [
                'itemId'        => $v[3],
                'itemCode'      => $v[3],
                'inventoryType' => $inventory[$v[5]],
                'actualQty'     => $v[4],
                'batchCode'     => '',
                'batchs'        => [
                    ## 批次暂时不处理，需要配合批次仓进行排序
                    'batch' => [
                        'batchCode' => '',
                        'productDate' => '',
                        'expireDate' => '',
                        'actualQty' => '',
                        'inventoryType' => '',
                    ]
                ]
            ];
        }
        return $orderLines;
    }
}
