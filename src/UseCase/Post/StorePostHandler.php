<?php

namespace App\UseCase\Post;

use App\DTO\Post\StorePostDTO;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Service\PostService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

class StorePostHandler
{
    public function __construct(
        private readonly PostService $postService,
        private readonly CategoryRepository $categoryRepository,
        // private readonly   $validator,
    ) {}

    public function execute(StorePostDTO $storePostDTO)
    {
        $post = new Post();

        $post->setTitle($storePostDTO->title);
        $post->setContent($storePostDTO->content);
        $post->setCategory($this->categoryRepository->getReference($storePostDTO->category));

        $this->postService->store($post);

        return $post;
    }
}
