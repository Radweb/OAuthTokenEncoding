<?php namespace Radweb\OAuthTokenEncoding;

use Psr\Http\Message\RequestInterface as PsrRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class AdaptorFactory {

	public function make($request, OAuthTokenEncoderInterface $encoder = null)
	{
		$encoder = $encoder ?: new OAuthTokenEncoder;

		if ($request instanceof PsrRequest)
		{
			return new OAuthTokenPsrAdaptor($encoder, $request);
		}
		elseif ($request instanceof SymfonyRequest)
		{
			return new OAuthTokenSymfonyAdaptor($encoder, $request);
		}
		else
		{
			throw new \InvalidArgumentException('Not provided with a PSR-7 or Symfony request object');
		}
	}

}