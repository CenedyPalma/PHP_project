<?php

namespace App\Repository;

use App\Entity\DiscussionPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DiscussionPost>
 */
class DiscussionPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiscussionPost::class);
    }

    /**
     * @return DiscussionPost[]
     */
    public function findByInventory(int $inventoryId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.inventory = :inventoryId')
            ->setParameter('inventoryId', $inventoryId)
            ->orderBy('p.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get posts after a certain ID for polling
     * @return DiscussionPost[]
     */
    public function findNewPosts(int $inventoryId, int $afterId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.inventory = :inventoryId')
            ->andWhere('p.id > :afterId')
            ->setParameter('inventoryId', $inventoryId)
            ->setParameter('afterId', $afterId)
            ->orderBy('p.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
