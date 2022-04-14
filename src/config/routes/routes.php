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
        '/user/show' => new FindUserByEmail(),
        '/post/show' => new FindPostById(),
        '/comment/show' => new FindCommentById(),
    ],
    'POST' => [
        '/user/create' => new CreateUser(),
        '/post/create' => new CreatePost(),
        '/comment/create' => new CreateComment(),
    ],
    'DELETE' => [
        '/user/delete' => new DeleteUser(),
        '/post/delete' => new DeletePost(),
        '/comment/delete' => new DeleteComment(),
    ]
];
