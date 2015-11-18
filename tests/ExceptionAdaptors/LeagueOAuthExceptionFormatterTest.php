<?php

namespace Radweb\OAuthTokenEncoding\Tests\ExceptionAdaptors;

use Mockery as m;
use League\OAuth2\Server\Exception\OAuthException;
use Radweb\OAuthTokenEncoding\ResponseAdaptors\Adaptor;
use Radweb\OAuthTokenEncoding\ResponseAdaptors\AdaptorFactory;
use Radweb\OAuthTokenEncoding\ExceptionAdaptors\LeagueOAuthExceptionFormatter;
use Radweb\OAuthTokenEncoding\Tests\TestCase;
use Zend\Diactoros\Request;

class LeagueOAuthExceptionFormatterTest extends TestCase {

	public function tearDown()
	{
		m::close();
	}

	public function testPassesExceptionDetailsThroughAnAdaptor()
	{
		$request = 'x';

		$e = new OAuthException('message here');
		$e->errorType = 'invalid_client';
		$e->httpStatusCode = 401;

		$mockAdaptor = m::mock(Adaptor::class)
			->shouldReceive('adapt')
			->with(['error' => 'invalid_client', 'error_description' => 'message here'], $e->httpStatusCode, $e->getHttpHeaders())
			->andReturn('this is a response')
			->getMock();

		$mockAdaptorFactory = m::mock(AdaptorFactory::class)
			->shouldReceive('make')
			->with($request)
			->andReturn($mockAdaptor)
			->getMock();

		$formatter = new LeagueOAuthExceptionFormatter($mockAdaptorFactory);

		$response = $formatter->handle($e, $request);

		$this->assertEquals('this is a response', $response);
	}


}