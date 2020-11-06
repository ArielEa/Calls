<?php

if ( !function_exists( 'convertMes' ) ) {
    /**
     * - 【转换xml】
     * @param $data
     * @param bool $root
     * @param string $xmlType
     * @return string
     */
    function convertMes( $data, $root = true, $xmlType = "" )
    {
        $str = "";
        if( $root ) $str .= "<request>";
        foreach($data as $key => $val) {
            if( is_array( $val ) ) {
                $child = convertMes( $val, false, $xmlType );

                if ( $xmlType != '' ) if (is_numeric( $key )) $key = $xmlType;

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
     * @return string
     */
    function convertXml( $param )
    {
        return "<?xml version=\"1.0\" encoding=\"utf-8\"?><response>". convertMes( $param, false ) . "</response>";
    }
}
