<?php

namespace App\Resource;

use App\Entity\Post;
use Symfony\Component\Serializer\SerializerInterface;

class PostResource
{
    public function __construct(
        private SerializerInterface $serializer
    ) {}


    public function list(array $posts)
    {
        return $this->serializer->serialize($posts, 'json', ['groups' => 'post:list']);
    }

    public function item(Post $post)
    {
        return $this->serializer->serialize($post, 'json', ['groups' => 'post:item']);
    }
}
