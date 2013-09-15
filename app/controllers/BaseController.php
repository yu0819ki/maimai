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
        $this->defaultContents = Config::get('mustache.defaultContents');
    }

    protected function setTitle($title)
    {
        $this->defaultContents['title'] = $title;
    }

    protected function setPageSettings($settings)
    {
        $this->defaultContents['page'] = array_merge($this->defaultContents['page'], $settings);
    }

}