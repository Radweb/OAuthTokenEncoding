<?php namespace Radweb\OAuthTokenEncoding;

trait HeadersForResponsesTrait {

	public function getHeadersForResponse($contentType)
	{
		return ['Content-Type' => $contentType, 'Cache-Control' => 'no-store'];
	}

}