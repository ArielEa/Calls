<?php

namespace Call\Request;

include_once 'ResultSet.php';
include_once '../Base/Project.php';

/**
 * - [ 发送请求 ]
 * Class TopClient
 * @package Call\Request
 */
class TopClient
{
    public $appKey;

    public $secretKey;

    public $targetAppKey = "";

    public $gatewayUrl = null;

    public $format = "xml";

    public $connectTimeout;

    public $readTimeout;

    /** 是否打开入参check**/
    public $checkRequest = true;

    protected $signMethod = "md5";

    protected $apiVersion = "2.0";

    public $sdkVersion = "top-sdk-php-20151012";

    public $customerid;

    public $body = "";

    private $specialCharacters = ["'", "&", "%", "+", "\\"];

    public function __construct($appKey = "",$secretKey = "")
    {
        $this->appKey = $appKey;
        $this->secretKey = $secretKey ;
    }

    /**
     * 签名 SIGN
     * @param array $params
     * @param string $body
     * @return string
     */
    private function generateSignDemo(array $params, string $body) : string
    {
        ksort($params);

        $stringToBeSigned = $this->secretKey;
        foreach ($params as $k => $v) {
            if(!is_array($v) && "@" != substr($v, 0, 1)) {
                $stringToBeSigned .= "$k$v";
            }
        }
        unset($k, $v);

        $stringToBeSigned .= $body;
        $stringToBeSigned .= $this->secretKey;
        return strtoupper(md5($stringToBeSigned));
    }

    /**
     * - 【 执行请求 】
     * @param $url
     * @param null $postFields
     * @return bool|string
     */
    public function curl($url, $postFields = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($this->readTimeout) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->readTimeout);
        }
        if ($this->connectTimeout) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        }
        curl_setopt ( $ch, CURLOPT_USERAGENT, "top-sdk-php" );

        //https 请求
        if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        if (is_array($postFields) && 0 < count($postFields)) {
            $postBodyString = "";
            $postMultipart = false;

            unset($k, $v);
            curl_setopt($ch, CURLOPT_POST, true);
            if ($postMultipart)
            {
                if (class_exists('\CURLFile')) {
                    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
                } else {
                    if (defined('CURLOPT_SAFE_UPLOAD')) {
                        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
                    }
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            }
            else
            {
                $header = array("content-type: application/x-www-form-urlencoded; charset=UTF-8");
                curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
//                curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString,0,-1));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->body);
            }
        }
        $reponse = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch),0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($reponse,$httpStatusCode);
            }
        }
        curl_close($ch);
        return $reponse;
    }

    /**
     * @param $url
     * @param null $postFields
     * @param null $fileFields
     * @return bool|string
     * @throws \Exception
     */
    public function curl_with_memory_file($url, $postFields = null, $fileFields = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($this->readTimeout) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->readTimeout);
        }
        if ($this->connectTimeout) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        }
        curl_setopt ( $ch, CURLOPT_USERAGENT, "top-sdk-php" );
        //https 请求

        if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        //生成分隔符
        $delimiter = '-------------' . uniqid();
        //先将post的普通数据生成主体字符串
        $data = '';
        if($postFields != null){
            foreach ($postFields as $name => $content) {
                $data .= "--" . $delimiter . "\r\n";
                $data .= 'Content-Disposition: form-data; name="' . $name . '"';
                //multipart/form-data 不需要urlencode，参见 http:stackoverflow.com/questions/6603928/should-i-url-encode-post-data
                $data .= "\r\n\r\n" . $content . "\r\n";
            }
            unset($name,$content);
        }

        //将上传的文件生成主体字符串
        if($fileFields != null){
            foreach ($fileFields as $name => $file) {
                $data .= "--" . $delimiter . "\r\n";
                $data .= 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $file['name'] . "\" \r\n";
                $data .= 'Content-Type: ' . $file['type'] . "\r\n\r\n";//多了个文档类型

                $data .= $file['content'] . "\r\n";
            }
            unset($name,$file);
        }
        //主体结束的分隔符
        $data .= "--" . $delimiter . "--";

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER , array(
                'Content-Type: multipart/form-data; boundary=' . $delimiter,
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $reponse = curl_exec($ch);
        unset($data);

        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch),0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new \Exception($reponse,$httpStatusCode);
            }
        }
        curl_close($ch);
        return $reponse;
    }

    /**
     * - 执行请求
     * @param $request - 各个接口传递过来的值，无法统一设定
     * @param null $xmlType - 生成xml中的部分值
     * @param null $session - session
     * @param null $bestUrl - bestUrl
     * @return mixed|ResultSet|SimpleXMLElement
     * @throws \Exception
     */
    public function execute($request, $xmlType = null, $session = null,$bestUrl = null)
    {
        if( $this->gatewayUrl == null ) {
            throw new \Exception("client-check-error:Need Set gatewayUrl.", 40);
        }
        $result =  new ResultSet();
        if($this->checkRequest) {
//            todo:: check 作用未知，暂不启用
//            try {
//                $request->check();
//            } catch (\Exception $e) {
//                $result->code = $e->getCode();
//                $result->msg = $e->getMessage();
//                return $result;
//            }
        }
        //组装系统参数
        $sysParams["app_key"] = $this->appKey;
        $sysParams["v"] = $this->apiVersion;
        $sysParams["format"] = $this->format;
        $sysParams["sign_method"] = $this->signMethod;
        $sysParams["method"] = $request->getApiMethodName();
        $sysParams["timestamp"] = date("Y-m-d H:i:s");
        //$sysParams["target_app_key"] = $this->targetAppKey;
        $sysParams['customerId'] = $this->customerid;
        if (null != $session) $sysParams["session"] = $session;
        //获取业务参数
        $apiParams = $request->getApiParas();
        $this->body = str_replace( $this->specialCharacters, "",  convertMes( $apiParams,  true, $xmlType ) );
        //系统参数放入GET请求串
        if ( $bestUrl ) {
            $requestUrl = $bestUrl."?";
            $sysParams["partner_id"] = $this->getClusterTag();
        } else {
            $requestUrl = $this->gatewayUrl."?";
            $sysParams["partner_id"] = $this->sdkVersion;
        }
        //签名
        $sysParams['sign'] = $this->generateSignDemo($sysParams, $this->body);

        foreach ($sysParams as $sysParamKey => $sysParamValue) {
            $requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
        }

        $fileFields = [];
        foreach ($apiParams as $key => $value) {
            if(is_array($value) && array_key_exists('type',$value) && array_key_exists('content',$value) ){
                $value['name'] = $key;
                $fileFields[$key] = $value;
                unset($apiParams[$key]);
            }
        }
        $requestUrl = substr($requestUrl, 0, -1);

        //发起HTTP请求
        try {
            if(count($fileFields) > 0){
                $resp = $this->curl_with_memory_file($requestUrl, $apiParams, $fileFields);
            }else{
                $resp = $this->curl($requestUrl, $apiParams);
            }
        } catch (\Exception $e) {
            $result->code = $e->getCode();
            $result->msg = $e->getMessage();
            return $result;
        }
        unset($apiParams);
        unset($fileFields);
        //解析TOP返回结果
        $respWellFormed = false;
        if ("json" == $this->format) {
            $respObject = json_decode($resp);
            if (null !== $respObject) {
                $respWellFormed = true;
                foreach ($respObject as $propKey => $propValue) {
                    $respObject = $propValue;
                }
            }
        } else if("xml" == $this->format) {
            $respObject = @simplexml_load_string($resp);
            if (false !== $respObject) {
                $respWellFormed = true;
            }
        }
        //返回的HTTP文本不是标准JSON或者XML，记下错误日志
        if (false === $respWellFormed) {
            $result->code = 0;
            $result->msg = "HTTP_RESPONSE_NOT_WELL_FORMED";
            return $result;
        }
        //如果TOP返回了错误码，记录到业务错误日志中
        if (isset($respObject->code)) {
            // 记录日志, 文件形式 txt 结尾
        }
        return $respObject;
    }

    /**
     * 版本记录
     * @return string
     */
    private function getClusterTag()
    {
        return substr($this->sdkVersion,0,11)."-cluster".substr($this->sdkVersion,11);
    }
}
