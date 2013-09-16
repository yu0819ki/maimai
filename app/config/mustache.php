<?php
/*
 * マスタッシュ系設定
 */
return array(
    'defaultContents' => array(
        'title'  => 'Maimai',
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
            'title'       => 'Maimai',
            'description' => '',
        ),
        'footer' => array(
            'headline'    => '',
            'body'        => 'This project is powered by Laravel framework, Mustache.php(mustache-l4), Guzzle, HTML5 Boilerplate and marked.',
            'copyright'   => '&copy; 2013 [yu0819ki](https://github.com/yu0819ki)'
        ),
        'bodyJs' => array(
            array('path' => '/js/vendor/marked.js'),
            array('path' => '/js/main.js'),
        ),
    ),
);