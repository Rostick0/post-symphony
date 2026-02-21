<?php

namespace App\Controller;

use App\DTO\Post\StorePostDTO;
use App\DTO\Post\UpdatePostDTO;
use App\Entity\Post;
use App\Query\Post\IndexPostHandler;
use App\Request\Post\IndexPostRequest;
use App\Request\Post\StorePostRequest;
use App\Request\Post\UpdatePostRequest;
use App\Resource\PostResource;
use App\UseCase\Post\RemovePostHandler;
use App\UseCase\Post\StorePostHandler;
use App\UseCase\Post\UpdatePostHandler;
use App\Utils\RequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/posts')]
class PostController extends AbstractController
{
    public function __construct(
        private RequestValidator $requestValidator,
        private PostResource $postResource,
    ) {}

    #[Route('', methods: ['GET', 'HEAD'])]
    public function index(Request $request, IndexPostHandler $handler): JsonResponse
    {
        $queries = $request->query->all();
        $indexRequest = IndexPostRequest::fromArray($queries);

        if ($validationError = $this->requestValidator->validate($indexRequest)) {
            return $validationError;
        }

        $postsResource = $handler->execute($queries);

        return new JsonResponse(
            data: $this->postResource->list([
                'data' => $postsResource->data,
                'total' => $postsResource->totals,
            ]),
            json: true
        );
    }

    #[Route('/{id:post}', methods: ['GET', 'HEAD'])]
    public function show(?Post $post): JsonResponse
    {
        return new JsonResponse(
            data: $this->postResource->item($post),
            json: true
        );
    }

    #[Route('', methods: ['POST'])]
    public function store(Request $request, StorePostHandler $handler): JsonResponse
    {
        $values = $request->toArray();

        $storeRequest = StorePostRequest::fromArray($values);

        if ($validationError = $this->requestValidator->validate($storeRequest)) {
            return $validationError;
        }

        $post = $handler->execute(
            StorePostDTO::fromArray($values)
        );

        return new JsonResponse(
            data: $this->postResource->item($post),
            json: true
        );
    }

    #[Route('/{id:post}', methods: ['PATCH'])]
    public function update(Request $request, Post $post, UpdatePostHandler $handler): JsonResponse
    {
        $values = $request->toArray();

        $updateRequest = UpdatePostRequest::fromArray($values);

        if ($validationError = $this->requestValidator->validate($updateRequest)) {
            return $validationError;
        }

        $post = $handler->execute(
            UpdatePostDTO::fromArray($values),
            $post,
        );

        return new JsonResponse(
            data: $this->postResource->item($post),
            json: true
        );
    }

    #[Route('/{id:post}', methods: ['DELETE'])]
    public function remove(Post $post, RemovePostHandler $handler): JsonResponse
    {
        $post = $handler->execute(
            $post,
        );

        return new JsonResponse(
            data: [
                'message' => 'Deleted'
            ],
            // json: true
        );
    }
}
