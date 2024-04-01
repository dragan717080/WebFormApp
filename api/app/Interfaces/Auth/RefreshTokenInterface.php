<?php

declare(strict_types = 1);

namespace App\Interfaces\Auth;

use Laravel\Passport\{ RefreshToken, Token };

interface RefreshTokenInterface 
{
    public function refreshToken(
        RefreshToken $refreshToken,
        Token $accessToken
        ): string|null;
}
