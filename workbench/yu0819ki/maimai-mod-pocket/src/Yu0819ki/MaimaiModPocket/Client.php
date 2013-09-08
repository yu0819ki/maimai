<?php namespace Yu0819ki\MaimaiModPocket;

use Guzzle\Http\Client as GuzzleClient;

class Client
{
    private $baseUrl = 'https://getpocket.com';
    private $consumerKey;

    private static $apiPath = array(
        'authorize'       => '/auth/authorize',
        'getAccessToken'  => '/v3/oauth/authorize',
        'getRequestToken' => '/v3/oauth/request',
        'retrieve'        => '/v3/get',
    );

    public function __construct($consumerKey, $baseUrl = null)
    {
        $this->consumerKey = $consumerKey;
        if ($baseUrl !== null) {
            $this->baseUrl = $baseUrl;
        }
    }

    public function getRequestToken($redirectUri = null)
    {
        $postBody = array(
            'consumer_key' => $this->consumerKey,
            'redirect_uri' => !empty($redirectUri) ? $redirectUri : $_SERVER['REQUEST_URI'],
        );
        return $this->request(array('apiPath' => 'getRequestToken', 'method' => 'post', 'postBody' => $postBody));
    }

    public function getAuthorizeUrl($requestToken, $redirectUri = null)
    {
        $requestQuery = array(
            'request_token' => $requestToken,
            'redirect_uri'  => !empty($redirectUri) ? $redirectUri : $_SERVER['REQUEST_URI'],
        );
        $uri =  $this->getApiPath('authorize', $requestQuery);

        return $uri;
    }

    public function getAccessToken($code)
    {
        $postBody = array(
            'consumer_key' => $this->consumerKey,
            'code' => $code,
        );
        return $this->request(array('apiPath' => 'getAccessToken', 'method' => 'post', 'postBody' => $postBody));
    }

    /**
     * Pocket It!
     *
     * @param  array $params Any Parameter...
     * @return mixed Return Anything...
     */
    public function request($params = array())
    {
        $return = null;
        $client = new GuzzleClient($this->baseUrl);

        $headers = array(
            'Content-type' => 'application/json; charset=UTF8;',
            'X-Accept'     => 'application/json',
        );

        // invalid request...
        if (!isset($params['apiPath'])) {
            return $return;
        }

        if (!isset($params['method'])) {
            $params['method'] = 'post';
        }

        $request = null;
        switch ($params['method']) {
            case 'post':
                $request  = $client->post($this->getApiPath($params['apiPath']), $headers, json_encode($params['postBody']));
                break;
        }

        // no request is there...
        if (empty($request)) {
            return $return;
        }

        try {
            $response = $request->send();
        } catch(Exception $e) {
            return $e->getMessage();
        }

        try {
            $return = $response->json();
        } catch(Exception $e) {
            $return = $response->getBody(true);
        }
        return $return;
    }

    public function getApiPath($type, $query = null)
    {
        if (!array_key_exists($type, self::$apiPath)) {
            return false;
        }

        $apiPath = $this->baseUrl . self::$apiPath[$type];

        if (is_array($query)) {
            $apiPath .= '?' . http_build_query($query);
        }

        return $apiPath;
    }
}