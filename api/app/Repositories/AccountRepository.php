<?php

namespace App\Repositories;

use App\Models\Account;
use App\Repositories\Traits\{  GetAllTrait, GetByIdTrait, DeleteTrait };
use App\Interfaces\{ ReadInterface, DeleteInterface };
use App\Interfaces\Account\{ CreateInterface, UpdateInterface };

class AccountRepository implements CreateInterface, ReadInterface,
    UpdateInterface, DeleteInterface
{
    use GetAllTrait;
    use GetByIdTrait;
    use DeleteTrait;

    public $model;

    public function __construct(private Account $account)
    {
        $this->model = $account;
    }

    public function update(
        string $id,
        ?string $name,
        ?string $website, 
        ?string $phone, 
    ): ?Account
    {
        $account = $this->model->find($id);

        if (!$account) {
            return null;
        }

        if ($name !== null) {
            $account->name = $name;
        }

        if ($website !== null) {
            $account->website = $website;
        }

        if ($phone !== null) {
            $account->phone = $phone;
        }

        $account->save();

        return $account;
    }

    /**
     * 
     * Creates account with credentials (default mode).
     */
    public function create(
        string $name,
        string $website,
        string $phone,
    ): Account
    {
        $account = new Account();

        $account->name = $name;
        $account->website = $website;
        $account->phone = $phone;

        $account->save();

        return $account;
    }
}
