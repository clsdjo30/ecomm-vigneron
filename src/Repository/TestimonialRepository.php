<?php

namespace App\Repository;

use App\Entity\Testimonial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Testimonial>
 */
class TestimonialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Testimonial::class);
    }

    /**
     * Find published testimonials ordered by display order
     */
    public function findPublished(?int $limit = null): array
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.isPublished = :published')
            ->setParameter('published', true)
            ->orderBy('t.displayOrder', 'ASC')
            ->addOrderBy('t.createdAt', 'DESC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Find testimonials with specific rating
     */
    public function findByRating(int $rating): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.isPublished = :published')
            ->andWhere('t.rating = :rating')
            ->setParameter('published', true)
            ->setParameter('rating', $rating)
            ->orderBy('t.displayOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
