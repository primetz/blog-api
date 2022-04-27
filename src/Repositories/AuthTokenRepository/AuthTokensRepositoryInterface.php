<?php

namespace App\Repositories\AuthTokenRepository;

use App\Entities\EntityInterface;
use App\Entities\Token\AuthTokenInterface;

interface AuthTokensRepositoryInterface
{
    public function getToken(string $token): ?AuthTokenInterface;

    public function getTokenByUser(EntityInterface $user): ?AuthTokenInterface;
}
