<?php

namespace App\Repository;

use App\Dao\Entity\TaxRateEntity;
use App\Enum\ExceptionEnum;
use App\Exception\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * @extends ServiceEntityRepository<TaxRateEntity>
 *
 * @method TaxRateEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaxRateEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaxRateEntity[]    findAll()
 * @method TaxRateEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaxRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaxRateEntity::class);
    }

    /**
     * @throws NotFoundException
     */
    public function findOneByCountryOrFail(string $country): TaxRateEntity
    {
        return $this->findOneBy(['country' => $country])
            ?? throw new NotFoundException(
            ExceptionEnum::COUNTRY_RATE_NOT_FOUND,
            Response::HTTP_NOT_FOUND
        );
    }
}
