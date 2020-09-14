<?php

namespace App\Repository;

use App\Entity\RbcNews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RbcNews|null find($id, $lockMode = null, $lockVersion = null)
 * @method RbcNews|null findOneBy(array $criteria, array $orderBy = null)
 * @method RbcNews[]    findAll()
 * @method RbcNews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RbcNewsRepository extends ServiceEntityRepository
{
    /**
     * RbcNewsRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RbcNews::class);
    }

    /**
     * @param RbcNews $rbcNews
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(RbcNews $rbcNews)
    {
        $this->_em->persist($rbcNews);
        $this->_em->flush();
    }
}
