<?php

use App\Http\Actions\UserActions\CreateUser;
use App\Http\Actions\UserActions\FindByEmail;

return [
    'GET' => [
        '/user/show' => new FindByEmail()
    ],
    'POST' => [
        '/user/create' => new CreateUser()
    ]
];
