<?php
include_once DIR_PATH."/phpexcel/Classes/PHPExcel/IOFactory.php";

/**
 * 自由文件，不限制地址, 不需要设置命名空间
 * Class ReadExcel
 */
class ReadExcel
{
    /**
     * 读取excel文件
     * @param $inputFileName
     * @param int $sheet
     * @return array
     * @throws \Exception
     */
    public function readExcelFile($inputFileName, $sheet = 0)
    {
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        // 默认的sheet
        $sheet = $objPHPExcel->getSheet($sheet);
        // 最大行数
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $excelRows = [];
        // ($row)数据从第二行开始，第一行是excel字段信息，提示信息
        for ($row = 2; $row <= $highestRow; $row++){
            // Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            // 这里得到的rowData都是一行的数据，获取数据后再次处理
            if (!empty($rowData)) {
                $excelRows[] = $rowData[0];
            }
        }
        return $excelRows;
    }
}
