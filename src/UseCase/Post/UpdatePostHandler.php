<?php

namespace App\UseCase\Post;

use App\DTO\Post\UpdatePostDTO;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Service\PostService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

class UpdatePostHandler
{
    public function __construct(
        private readonly PostService $postService,
        private readonly CategoryRepository $categoryRepository,
        // private readonly   $validator,
    ) {}

    public function execute(UpdatePostDTO $updatePostDTO, Post $post)
    {
        if ($updatePostDTO->title) {
            $post->setTitle($updatePostDTO->title);
        }

        if ($updatePostDTO->content) {
            $post->setContent($updatePostDTO->content);
        }

        if ($updatePostDTO->category) {
            $post->setCategory($this->categoryRepository->getReference($updatePostDTO->category));
        }

        $this->postService->save($post);

        return $post;
    }
}
