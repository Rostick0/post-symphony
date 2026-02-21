<?php

namespace App\DTO\Post;

class UpdatePostDTO
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $content = null,
        public readonly ?int $category = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            content: $data['content'] ?? null,
            category: $data['category'] ?? null,
        );
    }
}
