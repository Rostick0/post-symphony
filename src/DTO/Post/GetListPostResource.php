<?php

namespace App\DTO\Post;

class GetListPostResource
{
    public function __construct(
        public readonly array $data,
        public readonly int $totals,
    ) {}
}
