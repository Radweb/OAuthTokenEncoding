<?php

namespace Radweb\OAuthTokenEncoding\ResponseAdaptors;

use Radweb\OAuthTokenEncoding\OAuthTokenEncoder;
use Psr\Http\Message\RequestInterface as PsrRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Illuminate\Http\Request as IlluminateRequest;

class AdaptorFactory {

	private $adaptors = [
		IlluminateRequest::class => OAuthTokenIlluminateAdaptor::class,
		SymfonyRequest::class => OAuthTokenSymfonyAdaptor::class,
		PsrRequest::class => OAuthTokenPsrAdaptor::class,
	];

	public function make($request, OAuthTokenEncoder $encoder = null)
	{
		$encoder = $encoder ?: new OAuthTokenEncoder;

		foreach ($this->adaptors as $requestClass => $adaptorClass)
		{
			if ($request instanceof $requestClass)
			{
				return new $adaptorClass($encoder, $request);
			}
		}

		throw new \InvalidArgumentException('Not provided with a compatible Request object to create an OAuth Token Adaptor');
	}

}