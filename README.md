# Snip.ly PHP utilities

## Description

Tools for [Snip.ly](http://www.snip.ly)'s API, 
- OAuth2 feature with <https://github.com/thephpleague/oauth2-client>
- API Wrapper with <https://github.com/guzzle/guzzle/>

## Install

Requires PHP 5.4.

Via Composer

``` json
{
    "require": {
        "league/oauth2-client": "0.3.*@dev",
        "younes0/sniply": "dev-master"
    }
}
```

## Usage

### OAuth2

```php

$provider = new Younes0\Sniply\OAuth2Provider([
	'clientId'     => 'XXXXXXXX',
	'clientSecret' => 'XXXXXXXX',
	'redirectUri'  => 'https://your-registered-redirect-uri/'
]);

if ( ! isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    header('Location: '.$provider->getAuthorizationUrl());

} else {

	// Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorizationCode', [
    	'code' => $_GET['code']
    ]);

    $accessToken = $token->accessToken;

    echo $accessToken;
}
```

### API Wrapper

API methods: <http://snip.ly/api/>

```php

$sniply = new Younes0\Sniply\Client($accessToken);

$sniply->fetch() // Fetch All Snips Created By User
$sniply->fetch('foO') // Get A Specific Snip

$sniply->create($link, $message); //  Create a new snip
$sniply->edit('foO', $link, $message); // Edit a snip

// optional parameters for Pro plans
$sniply->create($link, $message, $optional = [
    'background_color' => '#fff',
    'message_color'    => '#000',
    'theme'            => 'fullwidth',
    'show_sniply_logo' => 'true', // string
    'button_action'    => [
        'text'             => $text,
        'url'              => $url,
        'background_color' => '#ff0000',
        'text_color'       => '#fff',
    ],
]);

```

You can access to Guzzle Response object instead of body by setting `$body` to `false` (usually the last parameter, check the source)

```php

// outputs guzzle response body as array (parsed json)
$response = $sniply->create($link, $message); 

// ouputs guzzle response object
$response = $sniply->create($link, $message, $optional, false);
$response->getReasonPhrase(); // 'CREATED'
echo $response->getBody(); // json
```

More infos at: <http://guzzle.readthedocs.org/en/latest/http-messages.html#body>

Finally, you can set a custom Guzzle Client which is useful when you need to mock responses or set a retry subscriber

```php
$mockAdapter = new MockAdapter(function (TransactionInterface $trans) {
    $request = $trans->getRequest();
    return new Response(200);
});

$sniply->setGuzzleClient(new \Guzzle\Client(['adapter' => $mockAdapter]));
```

More infos at: <http://guzzle.readthedocs.org/en/latest/testing.html#mock-adapter>

