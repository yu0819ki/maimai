<?php

/**
 * Maimai index
 *
 * @author yu0819ki<yu0819ki@gmail.com>
 * @package maimai
 */
class HomeController extends BaseController
{
    /**
     * Maimai index
     *
     * @param  void
     * @return void
     */
    public function index()
    {
        $this->loadDefaultContents();
        $this->setPageSettings(array(
            'title'       => 'Maimai project -- Home',
            'description' => '"Maimai project"は、各種Webサービスからいろいろな情報を取り込むことを目的としたWebサービスを作るために立ち上げたプロジェクトです。',
        ));
        $contents = $this->defaultContents;

        // 記事用設定
        $article = array(
            'section' => array(
                'headline' => '"Maimai project"について',
                'body'     =>
                    '各種Webサービスからいろいろな情報を取り込むことを目的としたWebサービスを作るために立ち上げたプロジェクトです。' . "\n\n" .
                    '現在は [Pocket](http://getpocket.com) にのみ対応しています。' . "\n"
                ,
            ),
        );

        // プリコンパイル
        $contents['mainContent'] = View::make('parts.elements.article_1', $article);
        $contents = $this->buildOpenGraphMeta($contents);

        // レンダリング
        $this->layout->with($contents);
    }
}