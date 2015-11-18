<?php namespace Radweb\OAuthTokenEncoding\Tests\ResponseAdaptors;

use InvalidArgumentException;
use Radweb\OAuthTokenEncoding\ResponseAdaptors\AdaptorFactory;
use Radweb\OAuthTokenEncoding\ResponseAdaptors\OAuthTokenIlluminateAdaptor;
use Radweb\OAuthTokenEncoding\ResponseAdaptors\OAuthTokenPsrAdaptor;
use Radweb\OAuthTokenEncoding\ResponseAdaptors\OAuthTokenSymfonyAdaptor;
use Radweb\OAuthTokenEncoding\Tests\TestCase;
use Zend\Diactoros\Request as PsrRequest;
use Illuminate\Http\Request as IlluminateRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class AdaptorFactoryTest extends TestCase {

	public function testPsr()
	{
		$this->assertInstanceOf(OAuthTokenPsrAdaptor::class, (new AdaptorFactory)->make(new PsrRequest));
	}

	public function testSymfony()
	{
		$this->assertInstanceOf(OAuthTokenSymfonyAdaptor::class, (new AdaptorFactory)->make(new SymfonyRequest));
	}

	public function testIlluminate()
	{
		$this->assertInstanceOf(OAuthTokenIlluminateAdaptor::class, (new AdaptorFactory)->make(new IlluminateRequest));
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionMessage Not provided with a compatible Request object to create an OAuth Token Adaptor
	 */
	public function testUnknownRequestsThrow()
	{
		$this->assertInstanceOf(OAuthTokenIlluminateAdaptor::class, (new AdaptorFactory)->make('foo'));
	}

}