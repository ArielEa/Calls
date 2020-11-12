<?php

namespace Call\Request;

/**
 * Interface ApiRequestInterface
 * @package Call\Request
 */
interface ApiRequestInterface
{
	public function request( $url, $postData, $postType = 'post', $status = 'diy' );
}
