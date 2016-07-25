<?php

namespace Radweb\OAuthTokenEncoding\Tests\ExceptionAdaptors;

use Illuminate\Http\Response;
use Mockery as m;
use League\OAuth2\Server\Exception\OAuthException;
use Radweb\OAuthTokenEncoding\ExceptionAdaptors\LaravelOAuthExceptionHandlingMiddleware;
use Radweb\OAuthTokenEncoding\ExceptionAdaptors\LeagueOAuthExceptionFormatter;
use Radweb\OAuthTokenEncoding\Tests\TestCase;
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

	public function testItCanHandleLaravel52ExceptionsInMiddleware()
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
			$response = new Response;
			$response->exception = $e;
			return $response;
		});

		$this->assertEquals($response, 'this is an example response');
	}

}