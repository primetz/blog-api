<?php

namespace App\Http\Auth;

use App\Entities\EntityInterface;
use App\Http\Request;

interface AuthenticationInterface
{
    public function getUser(Request $request): EntityInterface;
}
