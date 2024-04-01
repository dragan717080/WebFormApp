<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\TokenService;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private TokenService $tokenService
    ) {}

    public function getAuthorizationCode(): JsonResponse {
        return new JsonResponse(['message' => $this->authService->getAuthorizationCode()]);
    }

    public function getAccessToken(Request $req) {
        $params = $req->input();

        return $this->authService->getAccessToken(
            $params['code']
        );
    }

    public function storeAccessToken(Request $req): JsonResponse {
        $bodyParams = $req->input();
        try {
            $this->createToken(
                $bodyParams['clientId'],
                $bodyParams['accessToken'],
                $bodyParams['refreshToken'],
            );
            return 'Zoho Access Token created successfully.';
        } catch(\Exception $e) {
            return response()->json(['Creating Zoho Access Tokens failed' => $e], 400);
        }
    }

    public function createToken(
        $clientId,
        $accessTokenId,
        $refreshTokenId
        ): JsonResponse
    {
        try {
            $token = $this->tokenService->createToken($clientId, $accessTokenId);
            $refreshToken = $this->tokenService->createRefreshToken($refreshTokenId, $token->id);
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }

        return new JsonResponse([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
        ], 201);
    }
}
