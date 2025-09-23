<?php

namespace App\Repository;

use App\Dao\Entity\CouponEntity;
use App\Enum\ExceptionEnum;
use App\Exception\NotFoundException;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * @extends ServiceEntityRepository<CouponEntity>
 *
 * @method CouponEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method CouponEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method CouponEntity[]    findAll()
 * @method CouponEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CouponRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CouponEntity::class);
    }

    /**
     * @throws NotFoundException
     */
    public function findOneByCodeAndByIsActiveAndBetweenValidDatesOrFail(
        string $code,
        DateTimeImmutable $date,
        bool $isActive,
    ): CouponEntity
    {
        $coupon = $this->createQueryBuilder('c')
            ->andWhere('c.code = :code')
            ->andWhere('c.isActive = :active')
            ->andWhere('(c.validFrom IS NULL OR c.validFrom <= :now)')
            ->andWhere('(c.validTo IS NULL OR c.validTo >= :now)')
            ->setParameter('code', $code)
            ->setParameter('active', $isActive)
            ->setParameter('now', $date)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $coupon ?? throw new NotFoundException(
            ExceptionEnum::COUPON_CODE_NOT_FOUND,
            Response::HTTP_NOT_FOUND
        );
    }
}
