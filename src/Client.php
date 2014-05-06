<?php

namespace Younes0\Sniply;

class Client 
{

	protected $httpClient;
	protected $baseUrl = 'https://snip.ly/api/';
	public $accessToken;

	public function __construct($accessToken)
	{
		$this->httpClient = new \GuzzleHttp\Client(array(
		    'base_url' => array($this->baseUrl, array('version' => null)),
		    'defaults' => array(
		        'headers' => array(
		        	'Authorization' => 'Bearer '.$accessToken
		        ),
		    )
		));
	}

	/**
	 * Fetch All Snips Created By User or Get A Specific Snip
	 * 
	 * @param  String $slug    The slug is the three-character identifier that is included in the snip's URL
	 * @return Array|Guzzle\Http\Message\Response Guzzle response body|object
	 */
	public function fetch($slug = null, $body = true)
	{
		$url = $slug ? sprintf('snips/%s/', $slug) : 'snips/';

		return $this->output($this->httpClient->get($url), $body);
	}

	/**
	 * Create a new snip
	 * 
	 * @param  String $url     The url that you would like to shorten
	 * @param  String $message The text of the message that you would like to display in the snip. HTML messages are not supported
	 * @return Array|Guzzle\Http\Message\Response Guzzle response body|object
	 */
	public function create($url, $message, $body = true)
	{
		return $this->output(
 			$this->httpClient->post('snips/', array(
				'body' => array( 
					'url'     => $url,
					'message' => $message,
				),
			)
		), $body);		
	}

	/**
	 * Edit a snip
	 *
	 * @param  String $slug    The slug is the three-character identifier that is included in the snip's URL
	 * @param  String $url     The url that you would like to shorten
	 * @param  String $message The text of the message that you would like to display in the snip. HTML messages are not supported
	 * @return Array|Guzzle\Http\Message\Response Guzzle response body|object 
	 */
	public function edit($slug, $url, $message, $body = true)
	{
	 	return $this->output(
	 		$this->httpClient->post(sprintf('snips/%s/', $slug), array(
				'body' => array( 
					'url'     => $url,
					'message' => $message,
				)
			)
		), $body);	
	}

	/**
	 * [output description]
	 * @param  Guzzle\Http\Message\Response $response Guzzle response object 
	 * @param  boolean $body 
	 * @return Array|Guzzle\Http\Message\Response Guzzle response body|object 
	 */
	protected function output($response, $body) 
	{
		return $body ? $response->json() : $response;
	}

}
