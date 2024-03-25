<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyFixtures extends Fixture
{
    public static array $companies = [];

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 2; $i++) {
            $company = new Company();
            $manager->persist($company);
        }
        self::$companies[] = $company;
        $manager->flush();
    }
}
