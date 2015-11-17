<?php

namespace Radweb\OAuthTokenEncoding;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OAuthTokenSymfonyAdaptor implements Adaptor {

	use HeadersForResponsesTrait;

	/**
	 * @var Encoder
	 */
	private $encoder;

	/**
	 * @var Request
	 */
	private $request;

	public function __construct(Encoder $encoder, Request $request)
	{
		$this->encoder = $encoder;
		$this->request = $request;
	}

	public static function make(Request $request)
	{
		return new self(new OAuthTokenEncoder, $request);
	}

	public function adapt(array $tokens = [], $status = 200, array $headers = [])
	{
		list($contentType, $body) = $this->encoder->encode($this->request->headers->get('Accept'), $tokens);

		return new Response($body, $status, $this->getHeadersForResponse($contentType, $headers));
	}

}