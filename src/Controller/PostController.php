<?php

namespace App\Controller;

use App\Entity\Post;
use App\Request\Post\IndexPostRequest;
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
        private RequestValidator $requestValidator
    ) {}

    #[Route('', methods: ['GET', 'HEAD'])]
    public function index(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $indexRequest = IndexPostRequest::fromQuery($request->query->all());
        // dd($indexRequest);

        $validationError = $this->requestValidator->validate($indexRequest);
        if ($validationError) {
            return $validationError;
        }

        $postRepository = $entityManager->getRepository(Post::class);

        $filters = $indexRequest->toFilters();
        $posts = $postRepository->findByFilters($filters);
        $total = $postRepository->countByFilters($filters);

        $data = [];
        foreach ($posts as $post) {
            $data[] = [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'category' => [
                    'id' => $post->getCategory()->getId(),
                    'name' => $post->getCategory()->getName(),
                ],
            ];
        }

        return $this->json(
            [
                'data' => $data,
                'total' => $total,
            ]
        );
    }

    #[Route('/{id:post}', methods: ['GET', 'HEAD'])]
    public function show(?Post $post, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'category' => [
                'id' => $post->getCategory()->getId(),
                'name' => $post->getCategory()->getName(),
            ],
        ];

        return $this->json(
            $data
        );
    }
}
