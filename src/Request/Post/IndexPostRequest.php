<?php

namespace App\Request\Post;


use Symfony\Component\Validator\Constraints as Assert;

class IndexPostRequest
{
    #[Assert\Type('string')]
    #[Assert\Length(
        max: 1,
        maxMessage: 'Search query cannot be longer than {{ limit }} characters'
    )]
    public ?string $search = null;

    #[Assert\Type('string')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Title cannot be longer than {{ limit }} characters'
    )]
    public ?string $title = null;

    #[Assert\Positive(message: 'Category ID must be positive')]
    #[Assert\Type('integer')]
    public ?int $category_id = null;

    #[Assert\NotBlank]
    #[Assert\Choice(
        choices: ['id', 'title', 'createdAt'],
        message: 'Sort by must be one of: {{ choices }}'
    )]
    public string $sort_by = 'id';

    #[Assert\NotBlank]
    #[Assert\Choice(
        choices: ['ASC', 'DESC'],
        message: 'Sort order must be either ASC or DESC'
    )]
    public string $sort_order = 'DESC';

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    #[Assert\Positive(message: 'Limit must be positive')]
    #[Assert\Range(
        min: 1,
        max: 100,
        notInRangeMessage: 'Limit must be between {{ min }} and {{ max }}'
    )]
    public int $limit = 10;

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero(message: 'Offset must be zero or positive')]
    public int $offset = 0;

    /**
     * Создает DTO из query параметров Request
     */
    public static function fromQuery(array $query): self
    {
        $dto = new self();

        $dto->search = $query['search'] ?? null;
        $dto->title = $query['title'] ?? null;
        $dto->category_id = isset($query['category_id']) ? (int)$query['category_id'] : null;
        $dto->sort_by = $query['sort_by'] ?? 'id';
        $dto->sort_order = strtoupper($query['sort_order'] ?? 'DESC');
        $dto->limit = isset($query['limit']) ? (int)$query['limit'] : 10;
        $dto->offset = isset($query['offset']) ? (int)$query['offset'] : 0;

        return $dto;
    }

    /**
     * Конвертирует DTO в массив фильтров для репозитория
     */
    public function toFilters(): array
    {
        return [
            'search' => $this->search,
            'title' => $this->title,
            'category_id' => $this->category_id,
            'sort_by' => $this->sort_by,
            'sort_order' => $this->sort_order,
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }
}
