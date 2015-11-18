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
			return $next($request);
		}
		catch (OAuthException $e)
		{
			return $this->formatter->handle($e, $request);
		}
	}

}