<?php

namespace Radweb\OAuthTokenEncoding\ExceptionAdaptors;

use League\OAuth2\Server\Exception\OAuthException;
use Radweb\OAuthTokenEncoding\ResponseAdaptors\AdaptorFactory;

class LeagueOAuthExceptionFormatter {

	/**
	 * @var AdaptorFactory
	 */
	private $adaptors;

	public function __construct(AdaptorFactory $adaptors)
	{
		$this->adaptors = $adaptors;
	}

	public function handle(OAuthException $e, $request)
	{
		return $this->adaptors->make($request)->adapt([
			'error' => $e->errorType,
			'error_description' => $e->getMessage(),
		], $e->httpStatusCode, $e->getHttpHeaders());
	}

}