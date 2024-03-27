<?php

declare(strict_types = 1);

namespace App\Interfaces\Account;

interface UpdateInterface
{
    public function update(
        string $id,
        ?string $name,
        ?string $website,
        ?string $phone,
    );
}
