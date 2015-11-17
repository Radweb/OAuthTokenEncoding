<?php

namespace Radweb\OAuthTokenEncoding\Tests;

use League\OAuth2\Server\Exception\OAuthException;
use Psr\Http\Message\ResponseInterface;
use Radweb\OAuthTokenEncoding\AdaptorFactory;
use Radweb\OAuthTokenEncoding\LaravelOAuthExceptionHandlingMiddleware;
use Radweb\OAuthTokenEncoding\LeagueOAuthExceptionFormatter;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Zend\Diactoros\Request;

class LeagueOAuthExceptionFormatterTest extends TestCase {

	public function testSymfonyXML()
	{
		$this->handleSymfony(self::XML, self::XML_ERROR);
	}

	public function testSymfonyForm()
	{
		$this->handleSymfony(self::FORM, self::FORM_ERROR);
	}

	public function testSymfonyJSON()
	{
		$this->handleSymfony(self::JSON, self::JSON_ERROR);
	}

	public function testPsrXML()
	{
		$this->handlePsr(self::XML, self::XML_ERROR);
	}

	public function testPsrForm()
	{
		$this->handlePsr(self::FORM, self::FORM_ERROR);
	}

	public function testPsrJSON()
	{
		$this->handlePsr(self::JSON, self::JSON_ERROR);
	}

	private function handleSymfony($type, $expectedBody)
	{
		$request = SymfonyRequest::create('http://example.com', 'POST');
		$request->headers->set('Accept', $type);

		$response = $this->runRequest($request);

		$this->assertInstanceOf(SymfonyResponse::class, $response);
		$this->assertEquals(401, $response->getStatusCode());
		$this->assertEquals($expectedBody, (string) $response->getContent());
	}

	private function handlePsr($type, $expectedBody)
	{
		$request = new Request('http://example.com', 'POST', 'php://temp', ['Accept' => $type]);

		$response = $this->runRequest($request);

		$this->assertInstanceOf(ResponseInterface::class, $response);
		$this->assertEquals(401, $response->getStatusCode());
		$this->assertEquals($expectedBody, (string) $response->getBody());
	}

	private function runRequest($request)
	{
		$formatter = new LeagueOAuthExceptionFormatter(new AdaptorFactory);

		$e = new OAuthException('message here');
		$e->errorType = 'invalid_client';
		$e->httpStatusCode = 401;

		return $formatter->handle($e, $request);
	}

}