<?php

namespace Radweb\OAuthTokenEncoding\Tests;

use Radweb\OAuthTokenEncoding\OAuthTokenEncoder;

class OAuthTokenEncoderTest extends TestCase {

	public function testItReturnsJson()
	{
		$this->assertIsJson($this->execute(self::JSON));
	}

	public function testItDefaultsToJson()
	{
		$this->assertIsJson($this->execute(null));
		$this->assertIsJson($this->execute(''));
		$this->assertIsJson($this->execute('something/else'));
	}

	private function assertIsJson($response)
	{
		$this->assertResponse($response, self::JSON, self::JSON_BODY);
	}

	public function testItReturnsXml()
	{
		$response = $this->execute(self::XML);

		$this->assertResponse($response, self::XML, self::XML_BODY);
	}

	public function testItReturnsFormEncoded()
	{
		$response = $this->execute(self::FORM);

		$this->assertResponse($response, self::FORM, self::FORM_BODY);
	}

	private function execute($accept)
	{
		$encoder = new OAuthTokenEncoder;

		return $encoder->encode($accept, self::TOKENS);
	}

	/**
	 * @param array  $response
	 * @param string $contentType
	 * @param string $body
	 */
	private function assertResponse($response, $contentType, $body)
	{
		$this->assertCount(2, $response);

		$this->assertEquals($response[0], $contentType);
		$this->assertEquals($response[1], $body);
	}

}