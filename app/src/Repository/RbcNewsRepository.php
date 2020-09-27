<?php

namespace App\Repository;

use App\Entity\RbcNews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(RbcNews $rbcNews): void
    {
        $this->_em->persist($rbcNews);
        $this->_em->flush();
    }

    /**
     * @param RbcNews $oldNews
     * @param RbcNews $newNews
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return void
     */
    public function update(RbcNews $oldNews, RbcNews $newNews): void
    {
        $updatedNews = $oldNews->update(
            $newNews->getTitle(),
            $newNews->getContent(),
            $newNews->getTimestamp(),
            $newNews->getOriginalImageUrl(),
            $newNews->getImageTitle()
        );

        $this->save($updatedNews);
    }
}
