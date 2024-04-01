<?php

declare(strict_types = 1);

namespace App\Interfaces\Token;

use Laravel\Passport\{ RefreshToken, Token };

interface GetInterface
{
    public function getZohoAccessToken(): Token;

    public function getZohoRefreshToken(string $tokenId): RefreshToken;
}
