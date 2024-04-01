<?php

declare(strict_types = 1);

namespace App\Interfaces\Auth;

use Illuminate\Http\JsonResponse;

interface CreateInterface 
{
    public function createRecord(
        string $accessTokenId,
        string $moduleName,
        array $data
        ): JsonResponse;
}
