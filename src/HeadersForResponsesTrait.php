<?php

namespace Radweb\OAuthTokenEncoding;

trait HeadersForResponsesTrait {

	public function getHeadersForResponse($contentType, $headers = [])
	{
		return array_merge($headers, ['Content-Type' => $contentType, 'Cache-Control' => 'no-store']);
	}

}