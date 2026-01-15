<?php

namespace App\Repository;

use App\Entity\Inventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Inventory>
 */
class InventoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inventory::class);
    }

    /**
     * Find latest inventories
     * @return Inventory[]
     */
    public function findLatest(int $limit = 10): array
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find most popular inventories (by item count)
     * @return Inventory[]
     */
    public function findMostPopular(int $limit = 5): array
    {
        return $this->createQueryBuilder('i')
            ->select('i', 'COUNT(item.id) AS HIDDEN itemCount')
            ->leftJoin('i.items', 'item')
            ->groupBy('i.id')
            ->orderBy('itemCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all tags as a cloud (tag => count)
     * @return array<string, int>
     */
    public function getTagCloud(): array
    {
        $inventories = $this->createQueryBuilder('i')
            ->select('i.tags')
            ->getQuery()
            ->getResult();

        $tagCounts = [];
        foreach ($inventories as $inv) {
            foreach ($inv['tags'] as $tag) {
                $tag = trim($tag);
                if (!empty($tag)) {
                    $tagCounts[$tag] = ($tagCounts[$tag] ?? 0) + 1;
                }
            }
        }

        arsort($tagCounts);
        return array_slice($tagCounts, 0, 20);
    }

    /**
     * Find inventories by tag
     * @return Inventory[]
     */
    public function findByTag(string $tag): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.tags LIKE :tag')
            ->setParameter('tag', '%"' . $tag . '"%')
            ->orderBy('i.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Full-text search in name, description, and tags
     * Uses MySQL LIKE for compatibility (can be upgraded to FULLTEXT)
     * @return Inventory[]
     */
    public function fullTextSearch(string $query): array
    {
        $terms = preg_split('/\s+/', trim($query), -1, PREG_SPLIT_NO_EMPTY);

        if (empty($terms)) {
            return [];
        }

        $qb = $this->createQueryBuilder('i');

        foreach ($terms as $index => $term) {
            $param = 'term' . $index;
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('i.name', ':' . $param),
                    $qb->expr()->like('i.description', ':' . $param),
                    $qb->expr()->like('i.tags', ':' . $param)
                )
            );
            $qb->setParameter($param, '%' . $term . '%');
        }

        return $qb->orderBy('i.createdAt', 'DESC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }

    /**
     * Search using MySQL FULLTEXT (requires index)
     * Faster for large datasets
     * @return Inventory[]
     */
    public function nativeFullTextSearch(string $query): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT i.* FROM inventory i 
                WHERE MATCH(i.name, i.description) AGAINST(:query IN NATURAL LANGUAGE MODE)
                ORDER BY MATCH(i.name, i.description) AGAINST(:query IN NATURAL LANGUAGE MODE) DESC
                LIMIT 50";

        try {
            $results = $conn->executeQuery($sql, ['query' => $query])->fetchAllAssociative();

            $ids = array_column($results, 'id');
            if (empty($ids)) {
                return [];
            }

            return $this->createQueryBuilder('i')
                ->where('i.id IN (:ids)')
                ->setParameter('ids', $ids)
                ->getQuery()
                ->getResult();
        } catch (\Exception $e) {
            // Fallback to LIKE search if FULLTEXT index doesn't exist
            return $this->fullTextSearch($query);
        }
    }
}
