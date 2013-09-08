<?php

/**
 * The sample controller for using mustache template
 *
 * @author yu0819ki<yu0819ki@gmail.com>
 */
class MustacheSampleController extends BaseController {

    /** @type string $layout  set layout for mustache template */
    public $layout = 'layouts.mustache.master';

    /**
     * show sample page based on the H5bp(Initializr) Mustache template
     *
     * @param  void
     * @return void
     */
    public function show()
    {
        // basic settings for rendering a page
        $contents = array(
            'title'  => 'l4-mustache-h5bp-guzzle',
            'menues' => array(
                array(
                    'name' => 'Laravel',
                    'link' => 'http://laravel.com',
                ),
                array(
                    'name' => 'Mustache',
                    'link' => 'http://mustache.github.io/',
                ),
                array(
                    'name' => 'H5bp',
                    'link' => 'http://html5boilerplate.com/',
                ),
            ),
            'page'   => array(
                'title'       => 'page-title',
                'description' => 'page-description',
            ),
            'bodyJs' => array(
                array('path' => 'js/main.js'),
            ),
        );

        // the data for title-description model
        $data['title-description'] = array(
            'title'       => 'title',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sodales urna non odio egestas tempor. Nunc vel vehicula ante. Etiam bibendum iaculis libero, eget molestie nisl pharetra in. In semper consequat est, eu porta velit mollis nec.',
        );

        // the data for headline-body model
        $data['headline-body'] = array(
            'headline'    => 'headline',
            'body'        => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sodales urna non odio egestas tempor. Nunc vel vehicula ante. Etiam bibendum iaculis libero, eget molestie nisl pharetra in. In semper consequat est, eu porta velit mollis nec.',

        );

        // the data for a part of article
        $article = array(
            'header' => $data['title-description'],
            'section' => array(
                $data['headline-body'],
                $data['headline-body'],
                $data['headline-body'],
            ),
            'footer' => $data['headline-body'],
            'relation' => array(
                'aside' => array(
                    'title'       => 'guzzle',
                    'description' => 'Guzzle is a PHP HTTP client and framework for building RESTful web service clients http://guzzlephp.org/',
                ),
            ),
        );

        // precompile the article as the main content
        $contents['mainContent'] = View::make('parts.elements.article_1', $article);

        // render a page with contents data
        $this->layout->with($contents);
    }

}