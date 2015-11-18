<?php

namespace Radweb\OAuthTokenEncoding\ResponseAdaptors;

use Radweb\OAuthTokenEncoding\OAuthTokenEncoder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OAuthTokenSymfonyAdaptor implements Adaptor {

	use HeadersForResponsesTrait;

	/**
	 * @var OAuthTokenEncoder
	 */
	private $encoder;

	/**
	 * @var Request
	 */
	private $request;

	public function __construct(OAuthTokenEncoder $encoder, Request $request)
	{
		$this->encoder = $encoder;
		$this->request = $request;
	}

	public static function make(Request $request)
	{
		return new static(new OAuthTokenEncoder, $request);
	}

	public function adapt(array $tokens = [], $status = 200, array $headers = [])
	{
		list($contentType, $body) = $this->encoder->encode($this->request->headers->get('Accept'), $tokens);

		$responseClass = $this->getResponseClassName();

		return new $responseClass($body, $status, $this->getHeadersForResponse($contentType, $headers));
	}

	protected function getResponseClassName()
	{
		return Response::class;
	}

}