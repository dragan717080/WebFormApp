<?php

declare(strict_types = 1);

namespace App\Interfaces\Deal;

interface UpdateInterface
{
    public function update(
        string $id,
        ?string $name,
        ?string $stage,
    );
}
