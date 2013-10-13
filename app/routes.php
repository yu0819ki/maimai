<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');
Route::get('pocket',   'PocketController@index');
Route::get('pocket/entries', 'PocketController@entries');
Route::get('pocket/registor', 'PocketController@registor');
// Route::post('pocket/registor', 'PocketController@registor');

Route::post('pocket/registor', 
    array(
        'uses'   => 'PocketController@registor',
        'before' => 'csrf', 
    )
);

Route::get('auth/pocket',    'PocketController@auth');
