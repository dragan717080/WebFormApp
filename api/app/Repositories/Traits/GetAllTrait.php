<?php

namespace App\Repositories\Traits;

trait GetAllTrait
{
    public function getAll()
    {
        return $this->model->all();
    }
}
