<?php

interface ApiRequest
{
	public function request( $url, $postData, $postType = 'post' );
}