<?php

declare(strict_types = 1);

namespace App\Interfaces\Auth;

use Illuminate\Http\JsonResponse;

interface GetInterface 
{
    public function getAuthorizationCode(): string;

    public function getAccessToken(string $code): array;

    public function getAllRecords(
        string $accessTokenId,
        string $moduleName
    ): JsonResponse;
}
