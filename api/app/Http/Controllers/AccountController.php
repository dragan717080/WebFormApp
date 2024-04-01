<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\{ AuthService, TokenService };
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    protected $responseBuilder;

    public function __construct(
        protected TokenService $tokenService,
        private AuthService $authService,
    ) {}

    public function getAll(): JsonResponse {
        $accessTokenId = $this->tokenService->getZohoAccessToken()->id;

        return $this->authService->getAllRecords(
            $accessTokenId,
            'account'
        );
    }

    public function create(Request $req): JsonResponse
    {
        $accessTokenId = $this->tokenService->getZohoAccessToken()->id;

        $data = [
            'data' => [
                [
                    'Account_Name' => $req->input('name'),
                    'Website' => $req->input('website'),
                    'Phone' => $req->input('phone')
                ]
            ]
        ];

        return $this->authService->createRecord($accessTokenId, 'account', $data);
    }
}
