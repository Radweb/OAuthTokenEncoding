<?php

namespace Radweb\OAuthTokenEncoding\Tests;

use Mockery as m;
use League\OAuth2\Server\Exception\OAuthException;
use Radweb\OAuthTokenEncoding\LaravelOAuthExceptionHandlingMiddleware;
use Radweb\OAuthTokenEncoding\LeagueOAuthExceptionFormatter;
use Zend\Diactoros\Request;

class LaravelOAuthExceptionHandlingMiddlewareTest extends TestCase {

	public function tearDown()
	{
		m::close();
	}

	public function testIt()
	{
		$e = new OAuthException('message here');

		$request = new Request;

		$mockFormatter = m::mock(LeagueOAuthExceptionFormatter::class)
			->shouldReceive('handle')
			->with($e, $request)
			->andReturn('this is an example response')
			->getMock();

		$middleware = new LaravelOAuthExceptionHandlingMiddleware($mockFormatter);

		$response = $middleware->handle($request, function() use ($e) {
			throw $e;
		});

		$this->assertEquals($response, 'this is an example response');
	}

}