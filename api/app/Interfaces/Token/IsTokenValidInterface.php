<?php

declare(strict_types = 1);

namespace App\Interfaces\Token;

interface IsTokenValidInterface
{
    public function isTokenValid(
        string $expiresAt
    ): bool;
}
