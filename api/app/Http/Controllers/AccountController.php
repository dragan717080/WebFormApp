<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseModelController;
use App\Repositories\AccountRepository;
use Illuminate\Http\JsonResponse;

class AccountController extends BaseModelController
{
    protected $responseBuilder;

    public function __construct(protected AccountRepository $accountRepository) {
        parent::__construct($this->accountRepository);
    }

    public function create(Request $req)
    {
        return $this->responseBuilder->postResponse(
            $req->request->all(),
            ['name', 'website', 'phone']
        );
    }

    public function update(string $id, Request $req)
    {
        return $this->responseBuilder->updateResponse(
            $id,
            $req->request->all(),
            ['name', 'website', 'phone']
        );
    }
}
