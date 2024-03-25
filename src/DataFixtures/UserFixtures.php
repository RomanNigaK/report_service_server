<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public static array $users = [];
    public function load(ObjectManager $manager): void
    {
        $c = $manager->find(Company::class, CompanyFixtures::$companies[0]);

        $user1 = new User();
        $user1->setName("Ван");
        $user1->setSername("Оби");
        $user1->setPatronymic("Киноби");
        $user1->setPhone("00000000000");
        $user1->setRoles(["ROLE_DIRECTOR","ROLE_ADMIN"]);
        $user1->setEmail("director@m.ru");
        $user1->setPassword('$2y$13$HO71.mYbqymeBXuRPxQBwOL/zwn8xpIHBlccmKqMX.QqSBNxcedkG');
        $user1->setCompany($c);
        $manager->persist($user1);
        self::$users[] = $user1;

        $user2 = new User();
        $user2->setName("Бильбо");
        $user2->setSername("Беггенс");
        $user2->setPatronymic("");
        $user2->setPhone("00000000000");
        $user2->setRoles(["ROLE_ADMIN"]);
        $user2->setEmail("admin@m.ru");
        $user2->setPassword('$2y$13$HO71.mYbqymeBXuRPxQBwOL/zwn8xpIHBlccmKqMX.QqSBNxcedkG');
        $user2->setCompany($c);
        $manager->persist($user2);
        self::$users[] = $user2;

        $user3 = new User();
        $user3->setName("II");
        $user3->setSername("Арагорн");
        $user3->setPatronymic("Элессар");
        $user3->setPhone("00000000000");
        $user3->setRoles(["ROLE_MANAGER"]);
        $user3->setEmail("manager1@m.ru");
        $user3->setPassword('$2y$13$HO71.mYbqymeBXuRPxQBwOL/zwn8xpIHBlccmKqMX.QqSBNxcedkG');
        $user3->setCompany($c);
        $manager->persist($user3);
        self::$users[] = $user3;

        $user4 = new User();
        $user4->setName("Рокки");
        $user4->setSername("Бальбоа");
        $user4->setPatronymic("");
        $user4->setPhone("00000000000");
        $user4->setRoles(["ROLE_MANAGER"]);
        $user4->setEmail("manager2@m.ru");
        $user4->setPassword('$2y$13$HO71.mYbqymeBXuRPxQBwOL/zwn8xpIHBlccmKqMX.QqSBNxcedkG');
        $user4->setCompany($c);
        $manager->persist($user4);
        self::$users[] = $user4;

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CompanyFixtures::class,
        ];
    }
}
