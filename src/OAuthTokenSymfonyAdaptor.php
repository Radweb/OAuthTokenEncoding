<?php namespace Radweb\OAuthTokenEncoding;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OAuthTokenSymfonyAdaptor {

	use HeadersForResponsesTrait;

	/**
	 * @var OAuthTokenEncoder
	 */
	private $encoder;

	/**
	 * @var Request
	 */
	private $request;

	public function __construct(OAuthTokenEncoderInterface $encoder, Request $request)
	{
		$this->encoder = $encoder;
		$this->request = $request;
	}

	public static function make(Request $request)
	{
		return new self(new OAuthTokenEncoder, $request);
	}

	public function adapt($tokens)
	{
		list($contentType, $body) = $this->encoder->encode($this->request->headers->get('Accept'), $tokens);

		return new Response($body, 200, $this->getHeadersForResponse($contentType));
	}

}