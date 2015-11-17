# OAuth 2 Token Encoder

The OAuth 2 spec specifies token responses should be JSON. However XML users will be XML users so there's a draft spec extension which defines how OAuth responses should look in XML and Form Encoded formats:

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

### With Symfony

Given a Symfony HTTP Foundation request, the adaptor will check the `Accept` header of the request for `application/json`, `application/xml` or `application/x-www-form-urlencoded`. If none are found, it assumes JSON.

```php
$oauthToken = [
	"access_token" => "2YotnFZFEjr1zCsicMWpAA",
	"token_type" => "example",
	"expires_in" => 3600,
	"refresh_token" => "tGzv3JOkF0XG5Qx2TlKWIA",
	"example_parameter" => "example_value",
];

// $request should be a HTTP Foundation request

$adaptor = new OAuthTokenSymfonyAdaptor(new OAuthTokenEncoder, $request);
// or..
$adaptor = OAuthTokenSymfonyAdaptor::make($request);

$response = $adaptor->adapt($oauthToken);

// $response is now a HTTP Foundation response
```

##### With Laravel / Lumen [Laravel OAuth 2 Server](https://github.com/lucadegasperi/oauth2-server-laravel)

```php
use \LucaDegasperi\OAuth2Server\Authorizer;
use \Radweb\OAuthTokenEncoding\OAuthTokenSymfonyAdaptor;

Route::post('oauth/token', function(Authorizer $authorizer, OAuthTokenSymfonyAdaptor $adaptor) {
	return $adaptor->adapt($authorizer->issueAccessToken());
});
```

The response will contain the correctly encoded body, the correct `Content-Type` header and the `Cache-Control: no-store` header.

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

##### With Laravel 5.1 & [Laravel OAuth 2 Server](https://github.com/lucadegasperi/oauth2-server-laravel)

First [configure Laravel](http://laravel.com/docs/5.1/requests#psr7-requests) to work with PSR-7 requests by installing two packages.

```php
use \LucaDegasperi\OAuth2Server\Authorizer;
use \Radweb\OAuthTokenEncoding\OAuthTokenPsrAdaptor;

Route::post('oauth/token', function(Authorizer $authorizer, OAuthTokenPsrAdaptor $adaptor) {
	return $adaptor->adapt($authorizer->issueAccessToken());
});
```

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

