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
        'og'     => array(
            'type'        => 'website',
            'title'       => 'Maimai',
            'url'         => '/',
            'image'       => '/img/logo.png',
            'description' => ''
        ),
        'footer' => array(
            'headline'    => '',
            'body'        => 'This project is powered by Laravel framework, Mustache.php(mustache-l4), Guzzle, HTML5 Boilerplate and marked.',
            'copyright'   => '&copy; 2013 [yu0819ki](mailto:yu0819ki+maimai@gmail.com)'
        ),
        'bodyJs' => array(
            array('path' => '/js/vendor/marked.js'),
            array('path' => '/js/main.js'),
        ),
    ),
);