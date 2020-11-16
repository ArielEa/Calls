<?php

namespace Call\HttpFoundation;

/**
 * 返回json数据
 * Class JsonResponse
 * @package Call\HttpFoundation
 */
class JsonResponse
{
    /**
     * JsonResponse constructor.
     * @param $JsonResponse
     */
	public function __construct($JsonResponse)
	{
	    if (!$JsonResponse) {
            echo json_encode([]);
        } else {
	        echo json_encode($JsonResponse, JSON_UNESCAPED_UNICODE);
        }
	    die;
	}
}
