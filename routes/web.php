<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/api/users', ['uses' => 'UserController@getUsers']);

    $router->get('/api/users', 'UserController@index');//get all users records
    $router->post('/api/users', 'UserController@addUser');//create new user record
    $router->get('/api/users/{id}', 'UserController@show');//get user by id
    $router->put('/api/users/{id}', 'UserController@update');//update user record
    $router->patch('/api/users/{id}', 'UserController@update');//update user record
    $router->delete('/api/users/{id}', 'UserController@delete');//delete record

    //user job routes
    $router->get('/api/usersjob', 'UserJobController@index'); // Get all jobs
    $router->get('/api/usersjob/{id}', 'UserJobController@show'); // Get job by ID
    
