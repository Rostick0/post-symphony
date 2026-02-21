<?php

namespace App\Service;

use App\DTO\Post\GetListPostResource;
use App\Entity\Post;
use App\Repository\PostRepository;

class PostService
{
    public function __construct(
        private readonly PostRepository $repository,
    ) {}

    public function getList(array $queries): GetListPostResource
    {
        $posts = $this->repository->findByFilters($queries);
        $total = $this->repository->countByFilters($queries);

        return new GetListPostResource(
            data: $posts,
            totals: $total,
        );
    }

    public function save(Post $post)
    {
        $this->repository->save($post);

        $this->repository->flush();
    }

    public function remove(Post $post)
    {
        $this->repository->remove($post);

        $this->repository->flush();
    }
}
