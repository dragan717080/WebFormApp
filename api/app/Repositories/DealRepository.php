<?php

namespace App\Repositories;

use App\Models\Deal;
use App\Repositories\Traits\{  GetAllTrait, GetByIdTrait, DeleteTrait };
use App\Interfaces\{ ReadInterface, DeleteInterface };
use App\Interfaces\Deal\{ CreateInterface, UpdateInterface };

class DealRepository implements CreateInterface, ReadInterface,
    UpdateInterface, DeleteInterface
{
    use GetAllTrait;
    use GetByIdTrait;
    use DeleteTrait;

    public $model;

    public function __construct(private Deal $deal)
    {
        $this->model = $deal;
    }

    public function update(
        string $id,
        ?string $name,
        ?string $stage
    ): ?Deal
    {
        $deal = $this->model->find($id);

        if (!$deal) {
            return null;
        }

        if ($name !== null) {
            $deal->name = $name;
        }

        if ($stage !== null) {
            $deal->stage = $stage;
        }

        $deal->save();

        return $deal;
    }

    public function create(
        string $name,
        string $stage
    ): Deal
    {
        $deal = new Deal();

        $deal->name = $name;
        $deal->stage = $stage;

        $deal->save();

        return $deal;
    }
}
