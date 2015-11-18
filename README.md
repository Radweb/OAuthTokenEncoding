[<img src="http://i.imgur.com/Qslhr5z.png" align="right" height="40">](https://radweb.co.uk)

[![Build Status](https://api.travis-ci.org/Radweb/OAuthTokenEncoding.svg)](https://travis-ci.org/Radweb/OAuthTokenEncoding) [![Latest Stable Version](https://poser.pugx.org/radweb/oauth-token-encoding/v/stable)](https://packagist.org/packages/radweb/oauth-token-encoding) [![License](https://poser.pugx.org/radweb/oauth-token-encoding/license)](https://packagist.org/packages/radweb/oauth-token-encoding)

# OAuth 2 Token Encoder

The OAuth 2 spec specifies token responses should be JSON. However [XML users will be XML users](https://twitter.com/DanHarper7/status/514822464673951744) so there's a draft spec extension which defines how OAuth responses should look in XML and Form Encoded formats:

https://tools.ietf.org/html/draft-richer-oauth-xml-01

```json
{
	"access_token":"2YotnFZFEjr1zCsicMWpAA",
	"token_type":"example",
	"expires_in":3600
}
```

```xml
<oauth>
	<access_token>2YotnFZFEjr1zCsicMWpAA</access_token>
	<token_type>example</token_type>
	<expires_in>3600</expires_in>
</oauth>
```

```
access_token=2YotnFZFEjr1zCsicMWpAA&token_type=example&expires_in=3600
```

## Installation

```
composer require radweb\oauth-token-encoding
```

## Usage

There's a basic `Radweb\OAuthTokenEncoding\OAuthTokenEncoder` class which when given an `Accept` header and an array representing an OAuth token, will respond with the correct `Content-Type` header and the correctly encoded OAuth token.

There's also adaptors for common libraries which will respond with a correct Response object:

* `OAuthTokenIlluminateAdaptor` for Laravel
* `OAuthTokenSymfonyAdaptor` for Symfony
* `OAuthTokenPsrAdaptor` for any PSR-7 compatible libraries (although uses the `Zend\Diactoros` package as the implementation for the PSR-7 response)

Finally, if you're using the `League\OAuth2\Server` package, there's a compatible `LeagueOAuthExceptionFormatter` class for formatting exceptions from that library. If you're using it with Laravel, there's also `LaravelOAuthExceptionHandlingMiddleware` for doing that automatically.

### Basic Usage

```php
// grab the "Accept" header from your request and pass it in
$accept = 'application/xml';

// create an access token
$oauthToken = [
	"access_token" => "2YotnFZFEjr1zCsicMWpAA",
	"token_type" => "example",
	"expires_in" => 3600,
	"refresh_token" => "tGzv3JOkF0XG5Qx2TlKWIA",
	"example_parameter" => "example_value",
];

$encoder = new OAuthTokenEncoder;

list($contentType, $body) = $encoder->encode($accept, $oauthToken);

// return a response using the given body & content type
```

##### With League's OAuth 2 Server

The format returned by `League\OAuth2\Server\AuthorizationServer`'s `issueAccessToken` method can be passed through to the encoder.

```php
list($contentType, $body) = $encoder->encode($authorizationServer->issueAccessToken());
```

### With Laravel / Lumen

Given an `Illuminate\Http\Request` object, the adaptor will check the `Accept` header of the request for `application/json`, `application/xml` or `application/x-www-form-urlencoded`. If none are found, it assumes JSON.

```php
$oauthToken = [
	"access_token" => "2YotnFZFEjr1zCsicMWpAA",
	"token_type" => "example",
	"expires_in" => 3600,
	"refresh_token" => "tGzv3JOkF0XG5Qx2TlKWIA",
	"example_parameter" => "example_value",
];

// $request should be a Illuminate\Http\Request

$adaptor = new OAuthTokenIlluminateAdaptor(new OAuthTokenEncoder, $request);
// or..
$adaptor = OAuthTokenIlluminateAdaptor::make($request);

$response = $adaptor->adapt($oauthToken);

// $response is now an Illuminate\Http\Response
```

The response will contain the correctly encoded body, the correct `Content-Type` header and the `Cache-Control: no-store` header.

##### With [Laravel OAuth 2 Server](https://github.com/lucadegasperi/oauth2-server-laravel)

```php
use \LucaDegasperi\OAuth2Server\Authorizer;
use \Radweb\OAuthTokenEncoding\ResponseAdaptors\OAuthTokenIlluminateAdaptor;

Route::post('oauth/token', function(Authorizer $authorizer, OAuthTokenIlluminateAdaptor $adaptor) {
	return $adaptor->adapt($authorizer->issueAccessToken());
});
```

The format returned by `League\OAuth2\Server\AuthorizationServer`'s `issueAccessToken` method can be passed through to the encoder.

### With PSR-7

> To construct a response, the `zendframework/zend-diactoros` package is required.

Given a PSR-7 request, the adaptor will check the `Accept` header of the request for `application/json`, `application/xml` or `application/x-www-form-urlencoded`. If none are found, it assumes JSON.

```php
$oauthToken = [
	"access_token" => "2YotnFZFEjr1zCsicMWpAA",
	"token_type" => "example",
	"expires_in" => 3600,
	"refresh_token" => "tGzv3JOkF0XG5Qx2TlKWIA",
	"example_parameter" => "example_value",
];

// $request should be a PSR-7 compliant Request object

$adaptor = new OAuthTokenPsrAdaptor(new OAuthTokenEncoder, $request);
// or..
$adaptor = OAuthTokenPsrAdaptor::make($request);

$response = $adaptor->adapt($oauthToken);

// $response is now a PSR-7 compliant Response object
```

The response will contain the correctly encoded body, the correct `Content-Type` header and the `Cache-Control: no-store` header.

## Errors

If you're using [Laravel OAuth 2 Server](https://github.com/lucadegasperi/oauth2-server-laravel) you can use the `LaravelOAuthExceptionHandlingMiddleware` instead of the one provided in that package.

```json
{
	"error": "invalid_client",
	"error_description": "Client authentication failed."
}
```

```xml
<oauth>
	<error>invalid_client</error>
	<error_description>Client authentication failed.</error_description>
</oauth>
```

```
error=invalid_client&error_description=Client+authentication+failed.
```
