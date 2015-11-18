<?php

namespace Radweb\OAuthTokenEncoding\ResponseAdaptors;

interface Adaptor {

	public function adapt(array $tokens = [], $status = 200, array $headers = []);

}