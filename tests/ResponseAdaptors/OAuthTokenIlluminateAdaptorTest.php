<?php

namespace Radweb\OAuthTokenEncoding\Tests\ResponseAdaptors;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Radweb\OAuthTokenEncoding\ResponseAdaptors\OAuthTokenIlluminateAdaptor;
use Radweb\OAuthTokenEncoding\OAuthTokenEncoder;
use Mockery as m;
use Radweb\OAuthTokenEncoding\Tests\TestCase;

class OAuthTokenIlluminateAdaptorTest extends TestCase {

	public function tearDown()
	{
		m::close();
	}

	public function testItDelegatesToTheEncoderAndReturnsPsrResponseFromIt()
	{
		$accept = 'the-accept-language';
		$tokens = ['the' => 'tokens'];

		$mockEncoder = m::mock(OAuthTokenEncoder::class)
			->shouldReceive('encode')
			->with($accept, $tokens)
			->andReturn(['contentType', 'theBody'])
			->getMock();

		$adaptor = new OAuthTokenIlluminateAdaptor($mockEncoder, $this->makeRequest($accept));

		$response = $adaptor->adapt($tokens);

		$this->assertResponse($response, 'contentType', 'theBody');
	}

	public function testItHasStaticMakeMethodWhichConfiguresTheEncoder()
	{
		$adaptor = OAuthTokenIlluminateAdaptor::make($this->makeRequest(self::XML));

		$this->assertResponse($adaptor->adapt(self::TOKENS), self::XML, self::XML_BODY);
	}

	/**
	 * @param string $accept
	 * @return Request
	 */
	private function makeRequest($accept)
	{
		$request = Request::create('http://example.com', 'POST');
		$request->headers->set('Accept', $accept);
		return $request;
	}

	/**
	 * @param Response $response
	 * @param string $contentType
	 * @param string $body
	 */
	private function assertResponse(Response $response, $contentType, $body)
	{
		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals(200, $response->getStatusCode());
		$this->assertArraySubset(['content-type' => [$contentType], 'cache-control' => ['no-store, private']], $response->headers->all()); // symfony puts a "private" on the cache-control...
		$this->assertEquals($body, (string) $response->getContent());
	}

}