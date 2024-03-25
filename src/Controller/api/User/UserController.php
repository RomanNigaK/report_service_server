<?php

namespace App\Controller\api\User;

use App\Application\Model\Request\EmployeeModel;
use App\Application\Model\Request\UserModel;
use App\Application\Model\Response\UserModel as UserModelResponse;
use App\Entity\Company;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends AbstractController
{
    private $jwtManager;
    private $tokenStorageInterface;

    public function __construct(
        TokenStorageInterface $tokenStorageInterface,
        JWTTokenManagerInterface $jwtManager
    ) {
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    #[Route('/api/registration', methods: ['POST'])]
    public function Create(
        Request $request,
        ManagerRegistry $doctrine,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $doctrine->getRepository(User::class)->findOneBy(["email" => $request->toArray()["email"]]);

        dd($request);

        if ($user) {
            return $this->json(["data" => "A user with this email is registered"], 400);
        }

        $entityManager = $doctrine->getManager();

        $reguestData = UserModel::create($request);



        $user = new User($reguestData);


        $company = new Company();

        $entityManager->persist($company);

        $entityManager->flush();

        $user->setCompany($company);


        $plaintextPassword = $user->getPassword();

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);


        $user->setRoles(['ROLE_DIRECTOR', 'ROLE_ADMIN']);
        $entityManager->persist($user);



        $entityManager->flush();

        $createdUser = $doctrine->getRepository(User::class)->find($user->getId());

        return $this->json(["data" => UserModelResponse::create($createdUser)]);
    }

    #[Route('/api/login', methods: ['POST'])]
    public function Authorization(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $reguestData = UserModel::authorization($request);

        $user = $doctrine->getRepository(User::class)->findOneBy(["email" => $reguestData->getEmail()]);

        return $this->json(["data" => UserModelResponse::create($user)]);
    }

    #[Route('/api/auth', methods: ['GET'])]
    public function loadDataUser(
        ManagerRegistry $doctrine
    ): Response {

        $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());

        $user = $doctrine->getRepository(User::class)->findOneBy(["email" => $decodedJwtToken["email"]]);

        return $this->json(["data" => UserModelResponse::create($user)]);
    }
    #[Route('/api/employees', methods: ['GET'])]
    public function loadUsers(
        ManagerRegistry $doctrine
    ): Response {

        $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());

        $user = $doctrine->getRepository(User::class)->findOneBy(["email" => $decodedJwtToken["email"]]);

        $company =  $doctrine->getRepository(Company::class)->find($user->getCompany());

        $users = $doctrine->getRepository(User::class)->findBy(["company" => $company]);
        $collection = array();

        foreach ($users as $user) {
            $role = $user->getRoles();
            if (in_array("ROLE_ADMIN", $role) && !in_array("ROLE_DIRECTOR", $role)) {
                $collection[] = UserModelResponse::create($user, "ROLE_ADMIN");
            }
            if (in_array("ROLE_MANAGER", $role)) {
                $collection[] = UserModelResponse::create($user, "ROLE_MANAGER");
            }
            if (in_array("ROLE_DIRECTOR", $role)) {
                $collection[] = UserModelResponse::create($user, "ROLE_DIRECTOR");
            }
        }

        return $this->json(["data" => $collection]);
    }
    #[Route('/api/employee', methods: ['POST'])]
    public function add(
        Request $request,
        ManagerRegistry $doctrine,
        UserPasswordHasherInterface $passwordHasher
    ): Response {;



        if ($doctrine->getRepository(User::class)->findOneBy(["email" => $request->toArray()["email"]])) {
            return $this->json(["data" => "A user with this email is registered"], 400);
        }

        $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());

        $currentUser = $doctrine->getRepository(User::class)->findOneBy(["email" => $decodedJwtToken["email"]]);

        $entityManager = $doctrine->getManager();

        $reguestData = UserModel::addEmployee($request);

        $user = new User($reguestData);

        $company =  $doctrine->getRepository(Company::class)->find($currentUser->getCompany()->getId());

        $user->setCompany($company);

        $plaintextPassword = $user->getPassword();

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);


        $user->setRoles([$reguestData->getRole()]);

        $entityManager->persist($user);

        $entityManager->flush();

        $createdUser = $doctrine->getRepository(User::class)->find($user->getId());

        $role = $createdUser->getRoles();

        if (in_array("ROLE_ADMIN", $role)) {
            return $this->json(["data" => UserModelResponse::create($createdUser, "ROLE_ADMIN")]);
        }

        return $this->json(["data" => UserModelResponse::create($createdUser, "ROLE_MANAGER")]);
    }
}
