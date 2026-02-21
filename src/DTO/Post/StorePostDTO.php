<?php

namespace App\DTO\Post;

class StorePostDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $content,
        public readonly int $category,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            content: $data['content'],
            category: $data['category'],
        );
    }
}
