<?php namespace Radweb\OAuthTokenEncoding\Tests;

use League\OAuth2\Server\Exception\OAuthException;
use Psr\Http\Message\ResponseInterface;
use Radweb\OAuthTokenEncoding\AdaptorFactory;
use Radweb\OAuthTokenEncoding\LaravelOAuthExceptionHandlingMiddleware;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Zend\Diactoros\Request;

class LaravelOAuthExceptionHandlingMiddlewareTest extends TestCase {

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
		$this->assertEquals($expectedBody, (string) $response->getContent());
	}

	private function handlePsr($type, $expectedBody)
	{
		$request = new Request('http://example.com', 'POST', 'php://temp', ['Accept' => $type]);

		$response = $this->runRequest($request);

		$this->assertInstanceOf(ResponseInterface::class, $response);
		$this->assertEquals($expectedBody, (string) $response->getBody());
	}

	private function runRequest($request)
	{
		$middleware = new LaravelOAuthExceptionHandlingMiddleware(new AdaptorFactory);

		return $middleware->handle($request, function() {
			$e = new OAuthException('message here');
			$e->errorType = 'type here';
			throw $e;
		});
	}

}