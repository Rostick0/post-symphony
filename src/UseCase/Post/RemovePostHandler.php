<?php

namespace App\UseCase\Post;

use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Service\PostService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

class RemovePostHandler
{
    public function __construct(
        private readonly PostService $postService,
        // private readonly   $validator,
    ) {}

    public function execute(Post $post)
    {
        $this->postService->remove($post);

        return $post;
    }
}
