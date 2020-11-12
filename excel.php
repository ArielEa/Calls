<?php

include "./phpexcel/Classes/PHPExcel/IOFactory.php";

//elsx文件路径
$inputFileName = "./ExcelFiles/test.xlsx";

date_default_timezone_set('PRC');
// 读取excel文件
try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
} catch(\Exception $e) {
    echo $e->getMessage();
    die;
}
// 默认的sheet
$sheet = $objPHPExcel->getSheet(0);
$highestRow = $sheet->getHighestRow(); // 当前行数
$highestColumn = $sheet->getHighestColumn();

// 获取excel文件的数据，$row=2代表从第二行开始获取数据
$excelRows = [];
for ($row = 2; $row <= $highestRow; $row++){
// Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
//这里得到的rowData都是一行的数据，得到数据后自行处理，我们这里只打出来看看效果
    if (!empty($rowData)) {
        $excelRows[] = $rowData[0];
    }
}

print_r( $excelRows );
