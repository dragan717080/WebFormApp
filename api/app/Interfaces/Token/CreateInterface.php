<?php

declare(strict_types = 1);

namespace App\Interfaces\Token;

use Laravel\Passport\{ RefreshToken, Token };

interface CreateInterface
{
    public function createToken(
        string $clientId,
        string $accessToken
        ): Token;

        public function createRefreshToken(
            string $refreshTokenId,
            string $tokenId
        ): RefreshToken;
}
