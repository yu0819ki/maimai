<?php

use Yu0819ki\MaimaiModPocket\Client as Pocket;
use Yu0819ki\MaimaiModRedirect\Redirector as Redirector;

/**
 * Pocket連携コントローラ
 *
 * @author yu0819ki<yu0819ki@gmail.com>
 * @package maimai
 */
class PocketController extends BaseController
{
    protected $pocketClient;
    protected $authConfig;

    /** @type array $defaultContents  basic settings for rendering a page */
    protected $defaultContents = array(
        'title'  => 'maimai',
        'menues' => array(
            array(
                'name' => 'home',
                'link' => '/',
            ),
            array(
                'name' => 'pocket',
                'link' => '/pocket',
            ),
        ),
        'page'   => array(
            'title'       => 'page-title',
            'description' => 'page-description',
        ),
        'bodyJs' => array(
            array('path' => '/js/vendor/marked.js'),
            array('path' => '/js/main.js'),
        ),
    );

    /** @type string $layout  set layout for mustache template */
    public $layout = 'layouts.mustache.master';

    public function __construct()
    {
        $this->authConfig = Config::get('maimai-mod-pocket::auth');
        $this->pocketClient = new Pocket($this->authConfig['consumerKey']);
    }

    /**
     * インデックスページ
     *
     * @param  void
     * @return void
     */
    public function index()
    {
        $contents = $this->defaultContents;

        // the data for a part of article
        $article = array(
            'section' => array(
                array('body' => '[entries](' . URL::to('/pocket/entries') . ')'),
            ),
        );

        // precompile the article as the main content
        $contents['mainContent'] = View::make('parts.elements.article_1', $article);

        // render a page with contents data
        $this->layout->with($contents);
    }

    /**
     * Pocketに取り込んであるデータ一覧を表示
     *
     * @param  void
     * @return void
     */
    public function entries()
    {
        // アクセストークンの取得
        $accessToken = $this->getAccessToken('pocket', '/pocket/entries');
        if ($accessToken === false) {
            return;
        }

        $postBody = array(
            'consumer_key' => $this->authConfig['consumerKey'],
            'access_token' => $accessToken,
            'detailType'   => 'complete',
        );
        $result = $this->pocketClient->request(array('apiPath' => 'retrieve', 'method' => 'post', 'postBody' => $postBody));

        if (!isset($result['list'])) {
            // リストの取得に失敗
            App::abort('403', 'Forbidden');
        }

        $section = array();
        foreach ($result['list'] as $entry) {
            $section[] = array(
                'headline' => '[' . $entry['resolved_title'] .'](' . $entry['resolved_url'] . ')',
                'body'  => $entry['excerpt'],
            );
        }

        $contents = $this->defaultContents;

        // the data for a part of article
        $article = array(
            'section' => $section,
        );

        // precompile the article as the main content
        $contents['mainContent'] = View::make('parts.elements.article_1', $article);

        // render a page with contents data
        $this->layout->with($contents);
    }

    /**
     * 認証起点
     *
     * @param  void
     * @return void
     */
    public function auth()
    {
        $accessToken = $this->getAccessToken();

        if ($accessToken !== false) {
            $redirectUrl = '/pocket';

            // 戻り先が指定されていたら、リダイレクトURLをそこにする
            if (Session::has('authorize.pocket.callbackUrl')) {
                $redirectUrl = Session::get('authorize.pocket.callbackUrl');
                Session::forget('authorize.pocket.callbackUrl');
            }

            Redirector::execute($redirectUrl);
        }

        // アクセストークンの取得に失敗
        App::abort('403', 'Forbidden');
    }

    /**
     * アクセストークンの取得
     * ※アクセストークンを取得できるまでリダイレクトが走る
     *
     * @param  string $callbackUrl  認証完了後の遷移先URL
     * @return boolean|string  認証成功時はアクセストークン、失敗時はfalse
     */
    private function getAccessToken($callbackUrl = null)
    {
        $return = false;
        if (Session::has('authorized.pocket.access_token')) {
            $return = Session::get('authorized.pocket.access_token');
        } elseif (Session::has('authorize.pocket.request_token')) {
            $requestToken = Session::get('authorize.pocket.request_token');

            // リクエストトークンを再利用しないように、セッションから削除
            Session::forget('authorize.pocket.request_token');

            $result = $this->pocketClient->getAccessToken($requestToken);
            if (isset($result['access_token'])) {
                Session::put('authorized.pocket', $result);
                $return = $result['access_token'];
            } else {
                // リクエストトークン再取得からやりなおし
                Redirector::execute('/auth/pocket');
            }
        } else {
            // 認証成功時に戻すURLが指定してあったら、セッションに格納
            if ($callbackUrl !== null) {
                Session::put('authorize.pocket.callbackUrl', $callbackUrl);
            }

            $result = $this->pocketClient->getRequestToken();
            if (isset($result['code'])) {
                $requestToken = $result['code'];
                Session::put('authorize.pocket.request_token', $requestToken);
                $redirectUrl  = 'http://maimai.local/auth/pocket';
                $authorizeUrl = $this->pocketClient->getAuthorizeUrl($requestToken, $redirectUrl);
                Redirector::execute($authorizeUrl);
            }
        }

        return $return;
    }
}