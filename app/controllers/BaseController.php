<?php

/**
 * 基底コントローラ
 *
 * @author yu0819ki<yu0819ki@gmail.com>
 * @package maimai
 */
class BaseController extends Controller
{
    /** @type array $defaultContents  コンテンツ表示に利用する設定配列 */
    protected $defaultContents = array();

    /** @type string $layout  マスタッシュテンプレートによるレイアウト */
    public $layout = 'layouts.mustache.master';

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout))
        {
            $this->layout = View::make($this->layout);
        }
    }

    protected function loadDefaultContents($type = null)
    {
        $defaultContents = Config::get('mustache.defaultContents');
        if (isset($defaultContents['og']['url'])) {
            $defaultContents['og']['url'] = URL::to($defaultContents['og']['url']);
        }
        if (isset($defaultContents['og']['image'])) {
            $defaultContents['og']['image'] = URL::to($defaultContents['og']['image']);
        }

        $this->defaultContents = $defaultContents;
    }

    protected function setTitle($title)
    {
        $this->defaultContents['title'] = $title;
    }

    protected function buildOpenGraphMeta($contents)
    {
        if (!isset($contents['og']) || !is_array($contents['og'])) {
            return $contents;
        }

        if (!isset($contents['meta']) || !is_array($contents['meta'])) {
            $contents['meta'] = array();
        }

        foreach ($contents['og'] as $name => $content) {
            $contents['meta'][] = array(
                'name'    => 'og:' . $name,
                'content' => $content,
            );
        }

        return $contents;
    }

    protected function setPageSettings($settings)
    {
        $this->defaultContents['page'] = array_merge($this->defaultContents['page'], $settings);
        if (isset($settings['title'])) {
            $this->defaultContents['og']['title'] = $settings['title'];
        }
        if (isset($settings['description'])) {
            $this->defaultContents['og']['description'] = $settings['description'];
        }
    }

}