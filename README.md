# Snip.ly utilities

## Description

- OAuth2 feature, uses <https://github.com/thephpleague/oauth2-client>
- API Calls, uses <https://packagist.org/packages/guzzlehttp/guzzle>

## Install

Via Composer

``` json
{
    "require": {
        "younes0/sniply": "dev-master"
    }
}
```

## Usage

### OAuth2

```php

$provider = new Younes0\Sniply\Client(array(
	'clientId'     => 'XXXXXXXX',
	'clientSecret' => 'XXXXXXXX',
	'redirectUri'  => 'https://your-registered-redirect-uri/'
));

if ( ! isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    header('Location: '.$provider->getAuthorizationUrl());
    exit;

} else {

	// Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
    	'code' => $_GET['code']
    ]);

    // If you are using Eventbrite you will need to add the grant_type parameter (see below)
    $token = $provider->getAccessToken('authorization_code', [
		'code'       => $_GET['code'],
		'grant_type' => 'authorization_code'
    ]);

    echo $token->access_token;

}
```

### API Calls

```php

$client = new Younes0\Sniply\Client($accessToken);

$client->fetch() // Fetch All Snips Created By User
$client->fetch('foO') // Get A Specific Snip

$client->create($link, $message); //  Create a new snip
$client->create($slug, link, $message); // Edit a snip

```