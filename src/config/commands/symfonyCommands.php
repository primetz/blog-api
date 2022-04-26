<?php

use App\Commands\SymfonyCommands\Create\CreateUser;
use App\Commands\SymfonyCommands\Create\PopulateDB;
use App\Commands\SymfonyCommands\Delete\DeletePost;
use App\Commands\SymfonyCommands\Update\UpdateUser;

return [
    CreateUser::class,
    UpdateUser::class,
    DeletePost::class,
    PopulateDB::class,
];
