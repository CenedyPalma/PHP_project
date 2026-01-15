<?php

namespace App\Repository;

use App\Entity\ItemLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItemLike>
 */
class ItemLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemLike::class);
    }

    public function findByItemAndUser(int $itemId, int $userId): ?ItemLike
    {
        return $this->findOneBy(['item' => $itemId, 'user' => $userId]);
    }
}
