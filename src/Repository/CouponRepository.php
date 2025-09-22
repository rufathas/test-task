<?php

namespace App\Repository;

use App\Dao\Entity\CouponEntity;
use App\Enum\ExceptionEnum;
use App\Exception\NotFoundException;
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
        parent::__construct($registry, CouponRepository::class);
    }

    /**
     * @throws NotFoundException
     */
    public function findOneByCodeOrFail(string $code): CouponEntity
    {
        return $this->findOneBy(['code' => $code])
            ?? throw new NotFoundException(
            ExceptionEnum::COUPON_CODE_NOT_FOUND,
            Response::HTTP_NOT_FOUND
        );
    }
}
