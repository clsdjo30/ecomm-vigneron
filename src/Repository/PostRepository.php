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

    /**
     * Find published posts ordered by creation date
     */
    public function findPublished(int $limit = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.isPublished = :published')
            ->setParameter('published', true)
            ->orderBy('p.createdAt', 'DESC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Find published posts by category
     */
    public function findPublishedByCategory(int $categoryId): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.isPublished = :published')
            ->andWhere('p.category = :category')
            ->setParameter('published', true)
            ->setParameter('category', $categoryId)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find published posts by tag
     */
    public function findPublishedByTag(int $tagId): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.tags', 't')
            ->where('p.isPublished = :published')
            ->andWhere('t.id = :tag')
            ->setParameter('published', true)
            ->setParameter('tag', $tagId)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
