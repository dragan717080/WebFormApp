<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Http\ResponseBuilder;

class BaseModelController extends BaseController
{
    protected $responseBuilder;

    public function __construct(protected $repository) {
        $this->responseBuilder = new ResponseBuilder($repository);
    }

    public function getAll()
    {
        return $this->responseBuilder->getAllResponse();
    }

    public function getById(string $id)
    {
        return $this->responseBuilder->getByIdResponse($id);
    }

    public function delete(string $id)
    {
        return $this->responseBuilder->deleteResponse($id);
    }
}
