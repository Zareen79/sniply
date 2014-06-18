# Snip.ly PHP utilities

## Description

Tools for [Snip.ly](http://www.snip.ly)'s API, 
- OAuth2 feature, uses <https://github.com/thephpleague/oauth2-client>
- API Wrapper, uses <https://github.com/guzzle/guzzle/>

## Install

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

$provider = new Younes0\Sniply\OAuth2Provider(array(
	'clientId'     => 'XXXXXXXX',
	'clientSecret' => 'XXXXXXXX',
	'redirectUri'  => 'https://your-registered-redirect-uri/'
));

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

$client = new Younes0\Sniply\Client($accessToken);

$client->fetch() // Fetch All Snips Created By User
$client->fetch('foO') // Get A Specific Snip

$client->create($link, $message); //  Create a new snip
$client->edit('foO', $link, $message); // Edit a snip

// optional parameters for Pro plans
$client->create($link, $message, $optional = array(
    'background_color' => '#fff',
    'message_color'    => '#000',
    'theme'            => 'fullwidth',
    'show_sniply_logo' => 'true', // string
    'button_action'    => array(
        'text'             => $text,
        'url'              => $url,
        'background_color' => '#ff0000',
        'text_color'       => '#fff',
    ),
));

```

You can access to Guzzle Response object instead of body by setting `$body` to `false` (usually the last parameter, check the source)

```php

// outputs guzzle response body as array (parsed json)
$response = $client->create($link, $message); 

// ouputs guzzle response object
$response = $client->create($link, $message, $optional, false);
$response->getReasonPhrase(); // 'CREATED'
echo $response->getBody(); // json
```

More informations on: <http://guzzle.readthedocs.org/en/latest/http-messages.html#body>
