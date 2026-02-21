<?php

namespace App\Request\Post;


use Symfony\Component\Validator\Constraints as Assert;

class StorePostRequest
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Title query cannot be longer than {{ limit }} characters'
    )]
    public ?string $title = null;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Content cannot be longer than {{ limit }} characters'
    )]
    public ?string $content = null;

    #[Assert\NotBlank]
    #[Assert\Positive(message: 'Category must be positive')]
    #[Assert\Type('integer')]
    public ?int $category = null;

    public static function fromArray(array $array): self
    {
        $dto = new self();

        $dto->title = $array['title'];
        $dto->content = $array['content'];
        $dto->category = $array['category'];

        return $dto;
    }
}
