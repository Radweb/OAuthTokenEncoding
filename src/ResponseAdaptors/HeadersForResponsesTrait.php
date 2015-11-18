<?php

namespace Radweb\OAuthTokenEncoding\ResponseAdaptors;

trait HeadersForResponsesTrait {

	public function getHeadersForResponse($contentType, $headers = [])
	{
		return array_merge($headers, ['Content-Type' => $contentType, 'Cache-Control' => 'no-store']);
	}

}