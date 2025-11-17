<?php

namespace App\Repository;

use App\Entity\ContactMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContactMessage>
 */
class ContactMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactMessage::class);
    }

    /**
     * Find unread messages count
     */
    public function countUnread(): int
    {
        return $this->count(['isRead' => false]);
    }

    /**
     * Find recent unread messages
     */
    public function findRecentUnread(int $limit = 10): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.isRead = :isRead')
            ->setParameter('isRead', false)
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
