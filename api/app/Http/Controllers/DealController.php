<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseModelController;
use App\Repositories\DealRepository;
use Illuminate\Http\JsonResponse;

class DealController extends BaseModelController
{
    protected $responseBuilder;

    public function __construct(protected DealRepository $dealRepository) {
        parent::__construct($this->dealRepository);
    }

    public function create(Request $req)
    {
        return $this->responseBuilder->postResponse(
            $req->request->all(),
            ['name', 'stage']
        );
    }

    public function update(string $id, Request $req)
    {
        return $this->responseBuilder->updateResponse(
            $id,
            $req->request->all(),
            ['name', 'stage']
        );
    }
}
