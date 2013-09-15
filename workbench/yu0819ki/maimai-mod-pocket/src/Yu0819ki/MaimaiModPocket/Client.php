<?php namespace Yu0819ki\MaimaiModPocket;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\RequestException;
use Illuminate\Support\Facades\Log;

/**
 * OAuth Client of Pocket
 *
 * @author yu0819ki<yu0819ki@gmail.com>
 * @package maimai-mod-pocket
 */
class Client
{
    /** @type string $baseUrl  baseUrl of Pocket API */
    private $baseUrl = 'https://getpocket.com';

    /** @type string $consumerKey  OAuth ConsumerKey property */
    private $consumerKey;

    /** @type array $apiPath  API and Authorize path */
    private static $apiPath = array(
        'authorize'       => '/auth/authorize',
        'getAccessToken'  => '/v3/oauth/authorize',
        'getRequestToken' => '/v3/oauth/request',
        'retrieve'        => '/v3/get',
    );

    /**
     * The Constructor
     *
     * @param  string $consumerKey  OAuth ConsumerKey
     * @param  string $baseUrl      baseUrl of Pocket API (Optional)
     * @return void
     */
    public function __construct($consumerKey, $baseUrl = null)
    {
        $this->consumerKey = $consumerKey;
        if ($baseUrl !== null) {
            $this->baseUrl = $baseUrl;
        }
    }

    /**
     * Get Request Token
     *
     * @param  string $redirectUri  redirect to the uri, after got the token
     * @return mixed  expects void therefore redirecting
     */
    public function getRequestToken($redirectUri = null)
    {
        $postBody = array(
            'consumer_key' => $this->consumerKey,
            'redirect_uri' => !empty($redirectUri) ? $redirectUri : $_SERVER['REQUEST_URI'],
        );
        return $this->request(array('apiPath' => 'getRequestToken', 'method' => 'post', 'postBody' => $postBody));
    }

    /**
     * Get Url to Authorize
     *
     * @param  string $redirectUri  redirect to the uri, after got the token
     * @return string a url for authorizing
     */
    public function getAuthorizeUrl($requestToken, $redirectUri = null)
    {
        $requestQuery = array(
            'request_token' => $requestToken,
            'redirect_uri'  => !empty($redirectUri) ? $redirectUri : $_SERVER['REQUEST_URI'],
        );
        $uri =  $this->getApiPath('authorize', $requestQuery);

        return $uri;
    }

    /**
     * Get Request Token
     *
     * @param  string $code  auth code
     * @return mixed  expects AccessToken string
     */
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
        } catch(RequestException $e) {
            Log::error($e->getMessage());
            return false;//$e->getMessage();
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return false;//$e->getMessage();
        }

        try {
            $return = $response->json();
        } catch(Exception $e) {
            $return = $response->getBody(true);
        }
        return $return;
    }

    /**
     * Get API Path
     *
     * @see    self::$apiPath
     * @param  string $type  api type
     * @param  mixed  $query any parameters for building query-string to request
     * @return string api path with query-string
     */
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