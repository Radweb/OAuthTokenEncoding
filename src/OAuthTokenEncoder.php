<?php

namespace Radweb\OAuthTokenEncoding;

class OAuthTokenEncoder implements OAuthTokenEncoderInterface {

	const XML  = 'application/xml';
	const JSON = 'application/json';
	const FORM = 'application/x-www-form-urlencoded';

	public function encode($accept, $tokens)
	{
		switch ($this->parseAcceptHeader($accept))
		{
			case self::XML:
				return $this->asXml($tokens);
			case self::FORM:
				return $this->asForm($tokens);
			default:
				return $this->asJson($tokens);
		}
	}

	private function asXml($tokens)
	{
		$xml = '<oauth>';

		foreach ($tokens as $key => $value)
		{
			$xml .= "<$key>$value</$key>";
		}

		$xml .= '</oauth>';

		return [self::XML, $xml];
	}

	private function asForm($tokens)
	{
		return [self::FORM, http_build_query($tokens)];
	}

	private function asJson($tokens)
	{
		return [self::JSON, json_encode($tokens)];
	}

	private function parseAcceptHeader($accept)
	{
		return is_array($accept) && count($accept) ? $accept[0] : $accept;
	}

}