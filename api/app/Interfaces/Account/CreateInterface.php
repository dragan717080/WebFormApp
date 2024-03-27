<?php

declare(strict_types = 1);

namespace App\Interfaces\Account;

interface CreateInterface
{
    public function create(
        string $name,
        string $website,
        string $phone
    );
}
