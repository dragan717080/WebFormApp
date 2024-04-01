<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\TokenService;
use Illuminate\Support\Facades\Log;

class EnsureTokenIsValid
{
    public function __construct(
        protected AuthService $authService,
        protected TokenService $tokenService
    ) {}
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $req, Closure $next): Response
    {
        $accessToken = $this->tokenService->getZohoAccessToken();

        $isValid = $this->tokenService->isTokenValid($accessToken->expires_at);

        Log::info('Given access token: ' . $accessToken->id);
        if (!$isValid) {
            $refreshToken = $this->tokenService->getZohoRefreshToken($accessToken->id);
            Log::debug('Token is not valid, refreshing');

            $newAccessToken = $this->authService->refreshToken($refreshToken, $accessToken);
            if ($newAccessToken === null) {
                return response()->json('Failed to refresh token', 400);
            }
        } else {
            Log::info('Access token is valid, proceeding with request');
        }

        /**
         * After refreshing access token for Zoho API,
         * proceed to the corresponding controller route (Account/Deal).
         */
        return $next($req);
    }
}
