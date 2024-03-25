<?php

namespace App\Controller\api\Report;

use App\Application\Model\Response\ReportItemResponseModel;
use App\Application\Model\Response\ReportResponseModel;
use App\Application\Model\Response\UserResponseModel;
use App\Entity\Report;
use App\Entity\ReportItems;
use App\Entity\User;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ReportController extends AbstractController
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

    #[Route('/api/reports', methods: ['GET'])]
    public function Create(
        ManagerRegistry $doctrine,
    ): Response {

        $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());

        $user = $doctrine->getRepository(User::class)->findOneBy(["email" => $decodedJwtToken["email"]]);

        if (in_array("ROLE_ADMIN", $user->getRoles())) {


            $reports = $doctrine->getRepository(Report::class)->findAllByCompanyDESC($user->getCompany()->getId());
        } else {
            $reports = $doctrine->getRepository(Report::class)->findAllByIdDESC($user->getId());
        }

        $collections = array();

        foreach ($reports as $item) {
            $list = $doctrine->getRepository(ReportItems::class)->findBy(["report" => $item]);
            $collections[] = ReportResponseModel::create($item, $list);
        }

        return $this->json(["data" => $collections]);
    }

    #[Route('/api/report', methods: ['POST'])]
    public function loadDataUser(
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());

        $user = $doctrine->getRepository(User::class)->findOneBy(["email" => $decodedJwtToken["email"]]);

        $reports = $doctrine->getRepository(Report::class)->findBy(["company"=>$user->getCompany()]);

        $report  = new Report();

        $report->setUser($user);

        $report->setCompany($user->getCompany());

        $report->setOrdering(count($reports) + 1);

        $entityManager->persist($report);

        $entityManager->flush();

        $report = $doctrine->getRepository(Report::class)->find($report->getId());

        return $this->json(["data" => ReportResponseModel::create($report)]);
    }
}
