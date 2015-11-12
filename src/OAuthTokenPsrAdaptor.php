<?php

namespace Radweb\OAuthTokenEncoding;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

class OAuthTokenPsrAdaptor {

	const XML  = 'application/xml';
	const JSON = 'application/json';
	const FORM = 'application/x-www-form-urlencoded';

	/**
	 * @var OAuthTokenEncoder
	 */
	private $encoder;

	/**
	 * @var RequestInterface
	 */
	private $request;

	public function __construct(OAuthTokenEncoder $encoder, RequestInterface $request)
	{
		$this->encoder = $encoder;
		$this->request = $request;
	}

	public static function make(RequestInterface $request)
	{
		return new self(new OAuthTokenEncoder, $request);
	}

	public function adapt($tokens)
	{
		list($contentType, $body) = $this->encoder->encode($this->request->getHeader('Accept'), $tokens);

		return $this->makeResponse($body, $contentType);
	}

	/**
	 * @param string $body
	 * @param string $contentType
	 * @return Response
	 */
	private function makeResponse($body, $contentType)
	{
		return new Response($this->asStream($body), 200, [
			'Content-Type' => $contentType,
			'Cache-Control' => 'no-store',
		]);
	}

	/**
	 * @param $body
	 * @return StreamInterface
	 */
	private function asStream($body)
	{
		$stream = new Stream('php://temp', 'wb+');

		$stream->write($body);

		return $stream;
	}

}
