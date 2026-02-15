<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;;

class PostController
{
    #[Route('/posts', methods: ['GET', 'HEAD'])]
    public function index()
    {
        return new Response(
            json_encode(['val' => 1])
        );
    }
}
