<?php

namespace Call;

interface ApiRequest
{
	public function request( $url, $postData, $postType = 'post' );
}