<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findByFilters(array $filters): array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->addSelect('c');

        // Поиск по заголовку или содержанию
        if (!empty($filters['search'])) {
            $qb->andWhere('p.title LIKE :search OR p.content LIKE :search')
                ->setParameter('search', '%' . $filters['search'] . '%');
        }

        // Фильтр по категории
        if (!empty($filters['category_id'])) {
            $qb->andWhere('p.category = :category')
                ->setParameter('category', $filters['category_id']);
        }

        // Фильтр по заголовку
        if (!empty($filters['title'])) {
            $qb->andWhere('p.title LIKE :title')
                ->setParameter('title', '%' . $filters['title'] . '%');
        }

        // Сортировка
        $sortBy = $filters['sort_by'] ?? 'id';
        $sortOrder = $filters['sort_order'] ?? 'DESC';

        $allowedSortFields = ['id', 'title', 'createdAt'];
        if (in_array($sortBy, $allowedSortFields)) {
            $qb->orderBy('p.' . $sortBy, strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC');
        }

        // Пагинация
        if (!empty($filters['limit'])) {
            $qb->setMaxResults((int)$filters['limit']);
        }

        if (!empty($filters['offset'])) {
            $qb->setFirstResult((int)$filters['offset']);
        }

        return $qb->getQuery()->getResult();
    }

    public function countByFilters(array $filters): int
    {
        $qb = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)');

        if (!empty($filters['search'])) {
            $qb->andWhere('p.title LIKE :search OR p.content LIKE :search')
                ->setParameter('search', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category_id'])) {
            $qb->andWhere('p.category = :category')
                ->setParameter('category', $filters['category_id']);
        }

        if (!empty($filters['title'])) {
            $qb->andWhere('p.title LIKE :title')
                ->setParameter('title', '%' . $filters['title'] . '%');
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
    //    /**
    //     * @return Post[] Returns an array of Post objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
