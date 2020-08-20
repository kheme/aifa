<?php

$router->get('external-books',      'ExternalBookController@index');

$router->post('api/v1/books',       'BookController@store');