<?php

namespace Radweb\OAuthTokenEncoding\Tests\ResponseAdaptors;

use Radweb\OAuthTokenEncoding\ResponseAdaptors\OAuthTokenPsrAdaptor;
use Radweb\OAuthTokenEncoding\OAuthTokenEncoder;
use Radweb\OAuthTokenEncoding\Tests\TestCase;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;
use Mockery as m;

class OAuthTokenPsrAdaptorTest extends TestCase {

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
			->with([$accept], $tokens)
			->andReturn(['contentType', 'theBody'])
			->getMock();

		$adaptor = new OAuthTokenPsrAdaptor($mockEncoder, $this->makeRequest($accept));

		$response = $adaptor->adapt($tokens);

		$this->assertResponse($response, 'contentType', 'theBody');
	}

	public function testItHasStaticMakeMethodWhichConfiguresTheEncoder()
	{
		$adaptor = OAuthTokenPsrAdaptor::make($this->makeRequest(self::XML));

		$this->assertResponse($adaptor->adapt(self::TOKENS), self::XML, self::XML_BODY);
	}

	/**
	 * @param string $accept
	 * @return Request
	 */
	private function makeRequest($accept)
	{
		return new Request('http://example.com', 'POST', 'php://temp', ['Accept' => $accept]);
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
		$this->assertEquals(['Content-Type' => [$contentType], 'Cache-Control' => ['no-store']], $response->getHeaders());
		$this->assertEquals($body, (string) $response->getBody());
	}

}