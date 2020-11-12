<?php

namespace Call\HttpCurl;

interface ApiRequest
{
	public function sendRequest( $postData, $postType = 'post' );
}
