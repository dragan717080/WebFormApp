<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\{ RefreshToken, Token };
use App\Interfaces\Auth\{ GetInterface, CreateInterface, RefreshTokenInterface };

class AuthService implements GetInterface, CreateInterface, RefreshTokenInterface
{
    public function getAuthorizationCode(): string {
        $data = [
            'scope' => 'zohoCRM.modules.ALL',
            'client_id' => env('APP_CLIENT_ID'),
            'response_type' => 'code',
            'access_type' => 'offline',
            'redirect_uri' => env('CLIENT_BASE_URL')
        ];

        return 'https://accounts.zoho.com/oauth/v2/auth?' . http_build_query($data);
    }

    public function getAccessToken(string $code): array
    {
        $data = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => env('APP_CLIENT_ID'),
            'client_secret' => env('APP_CLIENT_SECRET'),
            'redirect_uri' => env('CLIENT_BASE_URL')
        ];

        $accessTokenUrl = 'https://accounts.zoho.eu/oauth/v2/token';

        $response = Http::asForm()->post($accessTokenUrl, $data);

        return $response->json();
    }

    /**
     * @return string|null $accessTokenId Access Token ID if found.
     */
    public function refreshToken(
        RefreshToken $refreshToken,
        Token $accessToken
        ): string|null {
        $data = [
            'refresh_token' => $refreshToken->id,
            // Alternatively could also retrieve them from 'oauth_clients' table
            'client_id' => env('APP_CLIENT_ID'),
            'client_secret' => env('APP_CLIENT_SECRET'),
            'grant_type' => 'refresh_token'
        ];

        $refreshTokenUrl = 'https://accounts.zoho.eu/oauth/v2/token?' . http_build_query($data);

        $response = Http::post($refreshTokenUrl);
        if (!($response->successful() && in_array('access_token', array_keys($response->json())))) {
            return null;
        }
        $accessToken->id = $response['access_token'];
        $accessToken->expires_at = now()->addHour();
        $accessToken->save();

        $refreshToken->access_token_id = $response['access_token'];
        $refreshToken->save();

        return $accessToken->id;
    }

    /**
     * Creates record in Zoho CRM Api.
     * 
     * @param string $accessTokenId ID of access token.
     * @param string $moduleName name of module lowercase, to know which URL
     * to create in (e.g. 'deal').
     * @param array $data data (Needs to have 'data' field in it for Zoho CRM).
     * 
     * @return JsonResponse $response
     */
    public function createRecord(
        string $accessTokenId,
        string $moduleName,
        array $data
        ): JsonResponse {
        $createRecordResponse = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessTokenId,
            'Content-Type' => 'application/json',
        ])->post('https://www.zohoapis.eu/crm/v2/' . $moduleName . 's?per_page=200&page=1', $data);

        if ($createRecordResponse->status() !== 201) {
            return response()->json(
                ['message' => 'Failed to create deal: ' . $createRecordResponse->json()['message'] ?? ''],
                400
            );
        }

        return response()->json(
            [
                'Account_Name' => $data['data'][0]['Account_Name'],
                'id' => $createRecordResponse->json()['data'][0]['details']['id']
            ],
            201
        );
    }

    public function getAllRecords(
        string $accessTokenId,
        string $moduleName
        ): JsonResponse {
        $getAllRecordsResponse = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessTokenId,
            'Content-Type' => 'application/json',
        ])->get('https://www.zohoapis.eu/crm/v2/' . $moduleName . 's?per_page=15&page=1');

        return $getAllRecordsResponse->status() === 200
            ? response()->json(['data' => $getAllRecordsResponse->json()['data']],)
            : response()->json([
                'message' => 'Failed to get ' . $moduleName . 's' .$getAllRecordsResponse->json()['message'] ?? ''
            ], 400);
    }
}
