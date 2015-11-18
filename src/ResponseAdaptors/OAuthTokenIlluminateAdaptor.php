<?php

namespace Radweb\OAuthTokenEncoding\ResponseAdaptors;

use Illuminate\Http\Response;

class OAuthTokenIlluminateAdaptor extends OAuthTokenSymfonyAdaptor {

	protected function getResponseClassName()
	{
		return Response::class;
	}

}