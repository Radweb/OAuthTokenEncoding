<?php

namespace Radweb\OAuthTokenEncoding\ResponseAdaptors;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Radweb\OAuthTokenEncoding\OAuthTokenEncoder;

class OAuthTokenIlluminateAdaptor extends OAuthTokenSymfonyAdaptor {

	public function __construct(OAuthTokenEncoder $encoder, Request $request)
	{
		parent::__construct($encoder, $request);
	}

	protected function getResponseClassName()
	{
		return Response::class;
	}

}