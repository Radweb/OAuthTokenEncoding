<?php

namespace Radweb\OAuthTokenEncoding;

use League\OAuth2\Server\Exception\OAuthException;

class LaravelOAuthExceptionHandlingMiddleware {

	/**
	 * @var AdaptorFactory
	 */
	private $adaptors;

	public function __construct(AdaptorFactory $adaptors)
	{
		$this->adaptors = $adaptors;
	}

	public function handle($request, \Closure $next)
	{
		try
		{
			return $next($request);
		}
		catch (OAuthException $e)
		{
			return $this->adaptors->make($request)->adapt([
				'error' => $e->errorType,
				'error_description' => $e->getMessage(),
			], $e->httpStatusCode, $e->getHttpHeaders());
		}
	}

}