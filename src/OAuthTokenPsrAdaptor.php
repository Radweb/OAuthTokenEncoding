<?php

namespace Radweb\OAuthTokenEncoding;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

class OAuthTokenPsrAdaptor implements Adaptor {

	use HeadersForResponsesTrait;

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

	public function adapt(array $tokens = [], $status = 200, array $headers = [])
	{
		list($contentType, $body) = $this->encoder->encode($this->request->getHeader('Accept'), $tokens);

		return new Response($this->asStream($body), $status, $this->getHeadersForResponse($contentType, $headers));
	}

	/**
	 * @param string $body
	 * @return StreamInterface
	 */
	private function asStream($body)
	{
		$stream = new Stream('php://temp', 'wb+');

		$stream->write($body);

		return $stream;
	}

}
