<?php

namespace App\Request\Post;


use Symfony\Component\Validator\Constraints as Assert;

class UpdatePostRequest
{
    #[Assert\Type('string')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Title query cannot be longer than {{ limit }} characters'
    )]
    public ?string $title = null;

    #[Assert\Type('string')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Content cannot be longer than {{ limit }} characters'
    )]
    public ?string $content = null;

    #[Assert\Positive(message: 'Category must be positive')]
    #[Assert\Type('integer')]
    public ?int $category = null;

    public static function fromArray(array $array): self
    {
        $dto = new self();

        $dto->title = $array['title'] ?? null;
        $dto->content = $array['content'] ?? null;
        $dto->category = $array['category'] ?? null;

        return $dto;
    }
}
