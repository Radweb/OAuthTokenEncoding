<?php

namespace Radweb\OAuthTokenEncoding\ExceptionAdaptors;

use League\OAuth2\Server\Exception\OAuthException;

class LaravelOAuthExceptionHandlingMiddleware {

	/**
	 * @var LeagueOAuthExceptionFormatter
	 */
	private $formatter;

	public function __construct(LeagueOAuthExceptionFormatter $formatter)
	{
		$this->formatter = $formatter;
	}

	public function handle($request, \Closure $next)
	{
		try
		{
			$response = $next($request);

			// Laravel 5.2 doesn't throw exceptions, it returns responses with it included
			if (isset($response->exception) && $response->exception)
			{
				throw $response->exception;
			}

			return $response;
		}
		catch (OAuthException $e)
		{
			return $this->formatter->handle($e, $request);
		}
	}

}