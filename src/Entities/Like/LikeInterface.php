<?php

namespace App\Entities\Like;

use App\Entities\EntityInterface;

interface LikeInterface extends EntityInterface
{
    public function getUserId(): int;

    public function getPostId(): int;
}
