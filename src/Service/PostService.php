<?php

namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepository;

class PostService
{
    public function __construct(
        private readonly PostRepository $repository,
    ) {}

    public function store(Post $post)
    {
        $this->repository->save($post);

        $this->repository->flush();
    }
}
