<?php

namespace Radweb\OAuthTokenEncoding;

interface Encoder {

	/**
	 * @param string|string[] $accept
	 * @param array $tokens
	 * @return array of two values - first being the content-type and second being the body
	 */
	public function encode($accept, $tokens);

}