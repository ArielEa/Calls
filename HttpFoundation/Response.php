<?php

namespace Call\HttpFoundation;

/**
 * 返回当前数据, 直接操作
 * Class Response
 * @package Call\HttpFoundation
 */
class Response
{
    protected $content;

    /**
     * Response constructor.
     * @param string $content
     * @param int $status
     * @param array $header
     * @throws \Exception
     */
	public function __construct($content = "", $status = 200, $header = [])
	{
	    if (is_array($content)) {
	        throw new \Exception("输出类型必须是string");
        }
        echo $content;die;
	}
}
