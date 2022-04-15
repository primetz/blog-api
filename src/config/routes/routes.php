<?php

use App\Http\Actions\Create\CreateUser;
use App\Http\Actions\Create\CreatePost;
use App\Http\Actions\Create\CreateComment;
use App\Http\Actions\Delete\DeleteComment;
use App\Http\Actions\Delete\DeletePost;
use App\Http\Actions\Delete\DeleteUser;
use App\Http\Actions\Find\FindCommentById;
use App\Http\Actions\Find\FindPostById;
use App\Http\Actions\Find\FindUserByEmail;

return [
    'GET' => [
        '/user/show' => FindUserByEmail::class,
        '/post/show' => FindPostById::class,
        '/comment/show' => FindCommentById::class,
    ],
    'POST' => [
        '/user/create' => CreateUser::class,
        '/post/create' => CreatePost::class,
        '/comment/create' => CreateComment::class,
    ],
    'DELETE' => [
        '/user/delete' => DeleteUser::class,
        '/post/delete' => DeletePost::class,
        '/comment/delete' => DeleteComment::class,
    ]
];
