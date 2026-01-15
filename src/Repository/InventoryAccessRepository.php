<?php

namespace App\Repository;

use App\Entity\InventoryAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InventoryAccess>
 */
class InventoryAccessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InventoryAccess::class);
    }

    /**
     * Find all access entries for an inventory
     * @return InventoryAccess[]
     */
    public function findByInventory(int $inventoryId): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.user', 'u')
            ->where('a.inventory = :inventoryId')
            ->setParameter('inventoryId', $inventoryId)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Check if a user has access to an inventory
     */
    public function hasAccess(int $inventoryId, int $userId): ?InventoryAccess
    {
        return $this->findOneBy([
            'inventory' => $inventoryId,
            'user' => $userId,
        ]);
    }
}
