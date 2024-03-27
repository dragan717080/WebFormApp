<?php

declare(strict_types = 1);

namespace App\Interfaces\Deal;

interface CreateInterface
{
    public function create(
        string $name,
        string $stage,
    );
}
