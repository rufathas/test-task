<?php

namespace App\Command\Seeder;

use App\Dao\Entity\CouponEntity;
use App\Dao\Entity\ProductEntity;
use App\Dao\Entity\TaxRateEntity;
use App\Dao\ValueObject\Money;
use App\Enum\CouponType;
use Brick\Math\BigDecimal;
use DateMalformedStringException;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:seed:test-data-seeder', description: 'Seed test data into the database')]
class TestDataSeeder extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
        parent::__construct();
    }

    /**
     * @throws DateMalformedStringException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $conn = $this->em->getConnection();
        try {
            $this->em->beginTransaction();
            $conn->executeStatement('TRUNCATE TABLE coupons RESTART IDENTITY CASCADE');
            $conn->executeStatement('TRUNCATE TABLE payments RESTART IDENTITY CASCADE');
            $conn->executeStatement('TRUNCATE TABLE products RESTART IDENTITY CASCADE');
            $conn->executeStatement('TRUNCATE TABLE purchase_idempotency_keys RESTART IDENTITY CASCADE');
            $conn->executeStatement('TRUNCATE TABLE purchases RESTART IDENTITY CASCADE');
            $conn->executeStatement('TRUNCATE TABLE tax_rates RESTART IDENTITY CASCADE');

            $products = [
                ['Iphone', Money::fromString('100.00')],
                ['Наушники', Money::fromString('20.00')],
                ['Чехол', Money::fromString('10.00')],
            ];
            $startDate = new DateTimeImmutable('yesterday');
            $endDate = $startDate->modify('+30 days');

            $coupons = [
                ['SUPERMAN10', CouponType::FIXED, BigDecimal::of('70.00'), $startDate, $endDate, true],
                ['SPIDERMAN74', CouponType::PERCENTAGE, BigDecimal::of('15.00'), $startDate, $endDate, true],
                ['IRONMAN23', CouponType::FIXED, BigDecimal::of('50.00'), $startDate, $endDate, false],
            ];

            $taxRates = [
                ['DE', 'DEXXXXXXXXX', BigDecimal::of('20.00')],
                ['IT', 'ITXXXXXXXXXXX', BigDecimal::of('10.00')],
                ['GR', 'GRXXXXXXXXX', BigDecimal::of('15.00')],
                ['FR', 'FRYYXXXXXXXXX', BigDecimal::of('17.00')],
            ];

            foreach ($products as [$name, $price]) {
                $productEntity = new ProductEntity($name, $price);
                $productEntity->setName($name);
                $productEntity->setPrice($price);
                $this->em->persist($productEntity);
            }

            foreach ($coupons as [$code, $type, $value, $validFrom, $validTo, $isActive]) {
                $couponEntity = new CouponEntity($code, $type, $value);
                $couponEntity->setValidFrom($validFrom);
                $couponEntity->setValidTo($validTo);
                $couponEntity->setIsActive($isActive);
                $this->em->persist($couponEntity);
            }

            foreach ($taxRates as [$countryCode, $mask, $rate]) {
                $taxRateEntity = new TaxRateEntity($countryCode, $rate, $mask);
                $this->em->persist($taxRateEntity);
            }

            $this->em->flush();
            $this->em->commit();

            $output->writeln('All data seeded successfully.');
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->em->rollback();
            $output->writeln('Seeding data failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
