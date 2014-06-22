<?php

namespace Younes0\Sniply;

class Client 
{

	static protected $baseUrl = 'https://snip.ly/api/';
	protected $guzzleClient;
	public $accessToken;

	public function __construct($accessToken)
	{
		$this->guzzleClient = new \GuzzleHttp\Client([
			'base_url' => [ static::$baseUrl, ['version' => null ] ],
			'defaults' => [
				'headers' => [
					'content-type'  => 'application/json',
					'Authorization' => 'Bearer '.$accessToken
				],
			]
		]);
	}

	/**
	 * Fetch All Snips Created By User or Get A Specific Snip
	 * 
	 * @param  String $slug	The slug is the three-character identifier that is included in the snip's URL
	 * @return Array|Guzzle\Http\Message\Response Guzzle response body|object
	 */
	public function fetch($slug = null, $body = true)
	{
		$path = $slug ? sprintf('snips/%s/', $slug) : 'snips/';

		return $this->output($this->guzzleClient->get($path), $body);
	}

	/**
	 * Create a new snip
	 * 
	 * @param  String $url	 The url that you would like to shorten
	 * @param  String $message The text of the message that you would like to display in the snip. HTML messages are not supported
	 * @return Array|Guzzle\Http\Message\Response Guzzle response body|object
	 */
	public function create($url, $message, Array $optional = [], $body = true)
	{
		return $this->createOrEdit(null, $url, $message, $optional, $body);
	}

	/**
	 * Edit a snip
	 *
	 * @param  String $slug	The slug is the three-character identifier that is included in the snip's URL
	 * @param  String $url	 The url that you would like to shorten
	 * @param  String $message The text of the message that you would like to display in the snip. HTML messages are not supported
	 * @param  Array $optional Additional parameters
	 * @return Array|Guzzle\Http\Message\Response Guzzle response body|object 
	 */
	public function edit($slug, $url, $message, Array $optional = [], $body = true)
	{
		return $this->createOrEdit($slug, $url, $message, $optional, $body);
	}

	/**
	 * Fetch profiles or specific profile
	 * @param  integer $id   profile id
	 * @param  boolean $body 
	 * @return Array|Guzzle\Http\Message\Response Guzzle response body|object 
	 */
	public function fetchProfiles($id = null, $body = true)
	{
		$path = $id ? sprintf('profiles/%s/', $id) : 'profiles/';

		return $this->output($this->guzzleClient->get($path), $body);
	}

	/**
	 * Edit a profile
	 * @param  integer $id       profile id
	 * @param  String  $name     profile name 
	 * @param  Array   $optional
	 * @param  boolean $body     
	 * @return Array|Guzzle\Http\Message\Response Guzzle response body|object 
	 */
	public function editProfile($id, $name, Array $optional = [], $body = true)
	{
		$path = sprintf('profiles/%s/', $id);

		return $this->output(
			$this->guzzleClient->post($path, [
				'body' => json_encode(array_merge($optional, [
					'name' => $name,
				])),
			]
		), $body);
	}

	/**
	 * Set a custom Guzzle client for http requests
	 * @param GuzzleHttpClient $client Custom Guzzle client
	 */
	public function setGuzzleClient(\GuzzleHttp\Client $client)
	{
		$this->guzzleClient = $client;

		return $this;
	}

	/**
	 * Create or Edit a snip
	 *
	 * @param  String $slug
	 * @param  String $url
	 * @param  String $message
	 * @param  Array $optional
	 * @return Array|Guzzle\Http\Message\Response Guzzle response body|object 
	 */
	protected function createOrEdit($slug = null, $url, $message, Array $optional = [], $body = true)
	{
		$path = $slug ? sprintf('snips/%s/', $slug) : 'snips/';

		return $this->output(
			$this->guzzleClient->post($path, [
				'body' => json_encode(array_merge($optional, [
					'url' => $url,
					'message' => $message,
				])),
			]
		), $body);
	}

	/**
	 * Output
	 * 
	 * @param  Guzzle\Http\Message\Response $response Guzzle response object 
	 * @param  boolean $body 
	 * @return Array|Guzzle\Http\Message\Response Guzzle response body|object 
	 */
	protected function output($response, $body) 
	{
		return $body ? $response->json() : $response;
	}

}
