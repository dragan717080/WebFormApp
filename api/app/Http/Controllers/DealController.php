<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class DealController extends Controller
{
    protected $responseBuilder;

    public function __construct(
        protected TokenService $tokenService,
        protected AuthService $authService,
    ) {}

    public function getAll(): JsonResponse {
        $accessTokenId = $this->tokenService->getZohoAccessToken()->id;

        $allDeals = $this->authService->getAllRecords(
            $accessTokenId,
            'deal'
        );

        return $allDeals;
    }

    public function create(Request $req): JsonResponse {
        $accessTokenId = $this->tokenService->getZohoAccessToken()->id;
        $account = $req->input('selected');

        $data = [
            'data' => [
                [
                    'Deal_Name' => $req->input('name'),
                    'Stage' => $req->input('stage'),
                    'Account_Name' => [
                        'id' => $account['id'],
                        'name' => $account['name']
                    ]
                ]
            ]
        ];

        return $this->authService->createRecord($accessTokenId, 'deal', $data);
    }
}
