<?php

namespace App\Resource;

use App\Entity\Post;
use Symfony\Component\Serializer\SerializerInterface;

class PostResource
{
    public function __construct(
        private SerializerInterface $serializer
    ) {}


    public function list(array $data)
    {
        return $this->serializer->serialize($data, 'json', ['groups' => 'post:list']);
    }

    public function item(Post $post)
    {
        return $this->serializer->serialize($post, 'json', ['groups' => 'post:item']);
    }
}
