<?php

namespace App\Repository;

use App\Entity\TodoItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TodoItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method TodoItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method TodoItem[]    findAll()
 * @method TodoItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TodoItem::class);
    }

    // /**
    //  * @return TodoItem[] Returns an array of TodoItem objects
    //  */
    public function getByAuthor($author)
    {
        return $this->createQueryBuilder('item')
            ->andWhere('item.author = :author')
            ->setParameter('author', $author)
            ->orderBy('item.dateCreate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?TodoItem
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
