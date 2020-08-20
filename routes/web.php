<?php

$router->get('external-books',      'ExternalBookController@index');

$router->get('api/v1/books',                'BookController@index');
$router->get('api/v1/books/{book_id}',      'BookController@show');
$router->patch('api/v1/books/{book_id}',    'BookController@update');
$router->post('api/v1/books',               'BookController@store');
$router->delete('api/v1/books/{book_id}',   'BookController@destroy');