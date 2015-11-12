<?php

namespace Radweb\OAuthTokenEncoding\Tests;

abstract class TestCase extends \PHPUnit_Framework_TestCase {

	const TOKENS = [
		"access_token" => "2YotnFZFEjr1zCsicMWpAA",
		"token_type" => "example",
		"expires_in" => 3600,
		"refresh_token" => "tGzv3JOkF0XG5Qx2TlKWIA",
		"example_parameter" => "example_value",
	];

	const XML = 'application/xml';

	const FORM = 'application/x-www-form-urlencoded';

	const JSON = 'application/json';

	const XML_BODY = '<oauth><access_token>2YotnFZFEjr1zCsicMWpAA</access_token><token_type>example</token_type><expires_in>3600</expires_in><refresh_token>tGzv3JOkF0XG5Qx2TlKWIA</refresh_token><example_parameter>example_value</example_parameter></oauth>';

	const FORM_BODY = 'access_token=2YotnFZFEjr1zCsicMWpAA&token_type=example&expires_in=3600&refresh_token=tGzv3JOkF0XG5Qx2TlKWIA&example_parameter=example_value';

	const JSON_BODY = '{"access_token":"2YotnFZFEjr1zCsicMWpAA","token_type":"example","expires_in":3600,"refresh_token":"tGzv3JOkF0XG5Qx2TlKWIA","example_parameter":"example_value"}';

}