<?php

namespace Radweb\OAuthTokenEncoding;

interface Adaptor {

	public function adapt(array $tokens = [], $status = 200, array $headers = []);

}