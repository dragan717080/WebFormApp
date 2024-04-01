<?php

namespace App\Services;

use Laravel\Passport\{ RefreshToken, Token };
use DateTime;
use App\Interfaces\Token\{ GetInterface, CreateInterface, IsTokenValidInterface };

/**
 * This Service class simply creates new tokens and
 * is not involved in direct token exchange.
 */
class TokenService implements GetInterface, CreateInterface, IsTokenValidInterface
{
    public function createToken(
        string $clientId,
        string $accessToken
        ): Token
    {
        $token = new Token();

        $token->id = $accessToken;
        $token->client_id = $clientId;
        /**
         * Name is to differ between Zoho access tokens and tokens that frontend uses to access Laravel API
         */
        $token->name='Zoho Access Token';
        $token->expires_at = now()->addMinutes(60);
        $token->scopes = [];
        $token->revoked = false;

        $token->save();

        return $token;
    }

    public function createRefreshToken(
        string $refreshTokenId,
        string $tokenId
        ): RefreshToken {
        $refreshToken = new RefreshToken();

        $refreshToken->id = $refreshTokenId;
        $refreshToken->access_token_id = $tokenId;
        $refreshToken->revoked = false;
        $refreshToken->expires_at = now()->addYear();

        $refreshToken->save();

        return $refreshToken;
    }

    /**
     * Since we have two types of access tokens
     * (one that client uses to connect with backend,
     * and one that backend uses to connect with Zoho),
     * 
     * we use token name 'Zoho Access Token' which
     * we assigned to access tokens to differentiate
     * 
     * Since Zoho access token was stored previously
     * theres no need to error handle, it's guaranteed to exist.
     * 
     * @return Token $zohoAccessToken
     */
    public function getZohoAccessToken(): Token {
        return Token::where('name', 'Zoho Access Token')->latest()->first();
    }

    public function getZohoRefreshToken(string $tokenId): RefreshToken {
        return RefreshToken::where('access_token_id', $tokenId)->first();
    }
    
    public function isTokenValid(string $expiresAt): bool {
        $currentDatetime = new DateTime();
        $targetDatetime = DateTime::createFromFormat('Y-m-d H:i:s', $expiresAt);

        return $targetDatetime && $targetDatetime > $currentDatetime;
    }
}
