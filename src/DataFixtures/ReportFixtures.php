<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Report;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReportFixtures extends Fixture implements DependentFixtureInterface
{
    public static array $reports = [];
    public int $ordering = 1;
    public function load(ObjectManager $manager): void
    {


        $c = $manager->find(Company::class, CompanyFixtures::$companies[0]);
        $u1 = $manager->find(User::class, UserFixtures::$users[0]);
        $u2 = $manager->find(User::class, UserFixtures::$users[1]);
        $u3 = $manager->find(User::class, UserFixtures::$users[2]);
        $u4 = $manager->find(User::class, UserFixtures::$users[3]);

        for ($i = 1; $i < 28; $i++) {
            $date = new DateTime('2024-02-' . $i . ' 17:16:18');
            $report1  = new Report($date);
            $report1->setCompany($c);
            $report1->setOrdering($this->ordering);
            $report1->setUser($u1);
            $manager->persist($report1);
            $this->ordering = $this->ordering + 1;
            self::$reports[] = $report1;

            $report2  = new Report($date);
            $report2->setCompany($c);
            $report2->setOrdering($this->ordering);
            $report2->setUser($u2);
            $manager->persist($report2);
            $this->ordering = $this->ordering + 1;
            self::$reports[] = $report2;

            $report3  = new Report($date);
            $report3->setCompany($c);
            $report3->setOrdering($this->ordering);
            $report3->setUser($u3);
            $manager->persist($report3);
            $this->ordering = $this->ordering + 1;
            self::$reports[] = $report3;

            $report4  = new Report($date);
            $report4->setCompany($c);
            $report4->setOrdering($this->ordering);
            $report4->setUser($u4);
            $manager->persist($report4);
            $this->ordering = $this->ordering + 1;
            self::$reports[] = $report4;
        }


        for ($i = 1; $i < 31; $i++) {
            $date = new DateTime('2024-03-' . $i . ' 17:16:18');
            $report1  = new Report($date);
            $report1->setCompany($c);
            $report1->setOrdering($this->ordering);
            $report1->setUser($u1);
            $manager->persist($report1);
            $this->ordering = $this->ordering + 1;
            self::$reports[] = $report1;

            $report2  = new Report($date);
            $report2->setCompany($c);
            $report2->setOrdering($this->ordering);
            $report2->setUser($u2);
            $manager->persist($report2);
            $this->ordering = $this->ordering + 1;
            self::$reports[] = $report2;

            $report3  = new Report($date);
            $report3->setCompany($c);
            $report3->setOrdering($this->ordering);
            $report3->setUser($u3);
            $manager->persist($report3);
            $this->ordering = $this->ordering + 1;
            self::$reports[] = $report3;

            $report4  = new Report($date);
            $report4->setCompany($c);
            $report4->setOrdering($this->ordering);
            $report4->setUser($u4);
            $manager->persist($report4);
            $this->ordering = $this->ordering + 1;
            self::$reports[] = $report4;
        }


        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CompanyFixtures::class,
            UserFixtures::class,
        ];
    }
}
