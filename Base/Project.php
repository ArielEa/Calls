<?php
/**
 * 公共函数调用部分
 */

if ( !function_exists( 'convertMes' ) ) {
    /**
     * - 【转换xml】
     * @param $data - 需要转换的数组数据
     * @param bool $root - 是否循环
     * @param string $xmlType - 是否标注字段, 一般特指 orderLine
     * @param bool $columnExplain - 是否处理特殊字符
     * @return string
     */
    function convertMes( $data, $root = true, $xmlType = "", $columnExplain = false )
    {
        $columnExplainFields = ['batch', 'item', 'package'];
        $str = "";
        if( $root ) $str .= "<request>";
        foreach($data as $key => $val) {
            if( is_array( $val ) ) {
                $child = convertMes( $val, false, $xmlType, $columnExplain );
                if ( $xmlType != '' ) {
                    if (is_numeric( $key )) {
                        $key = $xmlType;
                    }
                }
                ## 如果有特殊字段需要匹配，及时处理即可
                if ($columnExplain == true) {
                    $subRes = substr($key,0,strrpos($key,"_"));
                    if (in_array($subRes, $columnExplainFields)) {
                        $key = $subRes;
                    }
                }
                $str .= "<$key>{$child}</$key>";
            } else {
                $str.= "<$key>{$val}</$key>";
            }
        }
        if( $root ) $str .= "</request>";
        return $str;
    }
}

if ( !function_exists('convertXml') ) {
    /**
     * - 【 拼接XML(标准) 】
     * @param $param
     * @param string $specStr
     * @param $columnExplain bool
     * @return string
     */
    function convertXml( $param, $specStr = "orderLine", $columnExplain = false )
    {
        return "<?xml version=\"1.0\" encoding=\"utf-8\"?><response>". convertMes( $param, false, $specStr, $columnExplain ) . "</response>";
    }
}

if (!function_exists('parseXml')) {
    /**
     * @param $data
     * @return array|mixed
     * @throws \Exception
     */
    function parseXml( $data)
    {
        if (!xml_parse(xml_parser_create(), $data, true)) {
//            print_r( $data );
//            echo PHP_EOL;
            return ['code' => 500, 'flag' => 'failure', 'message' => '请输入正确的XML数据'];
        }
        return xmlToArray( $data );
    }
}

if (!function_exists('xmlToArray')) {
    /**
     * xml 转数组
     * @param string $xml
     * @return mixed
     */
    function xmlToArray($xml = '')
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }
}
