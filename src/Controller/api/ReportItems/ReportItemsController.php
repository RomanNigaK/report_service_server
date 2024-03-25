<?php

namespace App\Controller\api\ReportItems;

use App\Application\Exception\HttpRequestNotRequiredFild;
use App\Application\Model\Request\ReportItemsModel;
use App\Application\Model\Request\UpdateReportItemsModel;
use App\Application\Model\Response\ReportItemResponseModel;
use App\Application\Model\Response\UserResponseModel;
use App\Entity\Report;
use App\Entity\ReportItems;
use App\Entity\User;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ResponseList
{
    public $items, $reportId;
}


class ReportItemsController extends AbstractController
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

    #[Route('/api/report/{reportId}/list', methods: ['GET'])]
    public function Create(
        ManagerRegistry $doctrine,
        string $reportId,
    ): Response {

        $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());

        $user = $doctrine->getRepository(User::class)->findOneBy(["email" => $decodedJwtToken["email"]]);

        if (!$user) {
            return $this->json(["data" => "A user with this email is registered"], 401);
        }
        $report = $doctrine->getRepository(Report::class)->find($reportId);

        if($report===null){
            throw new HttpRequestNotRequiredFild("invalid identifier");
        }

        $reportItems = $doctrine->getRepository(ReportItems::class)->findBy(["report" => $report]);


        $response = new ResponseList();
        $collection=array();

        foreach ($reportItems as $item) {

            $collection[] = ReportItemResponseModel::create($item, $report->getUser()->getId());
        }

        $response->items = $collection;
        $response->reportId =  $reportId;

        return $this->json(["data" => $response]);
    }

    #[Route('/api/report/{reportId}/item', methods: ['POST'])]
    public function addItem(
        Request $request,
        ManagerRegistry $doctrine,
        string $reportId,
    ): Response {
        $entityManager = $doctrine->getManager();

        $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());

        $user = $doctrine->getRepository(User::class)->findOneBy(["email" => $decodedJwtToken["email"]]);

        $report = $doctrine->getRepository(Report::class)->find($reportId);

        $reportItem = new ReportItems(ReportItemsModel::create($request));

        $reportItem->setReport($report);

        $entityManager->persist($reportItem);

        $entityManager->flush();

        $reportItem = $doctrine->getRepository(ReportItems::class)->find($reportItem->getId());

        return $this->json(["data" => ReportItemResponseModel::create($reportItem, $user->getId())]);
    }

    #[Route('/api/item/{itemId}', methods: ['PUT'])]
    public function updateItem(
        Request $request,
        ManagerRegistry $doctrine,
        string $itemId,
    ): Response {
        $entityManager = $doctrine->getManager();

        $item = $entityManager->getRepository(ReportItems::class)->find($itemId);

        if (!$item) {
            throw $this->createNotFoundException(
                'Item not found'
            );
        }

        $project = UpdateReportItemsModel::create($request, $item);

        $entityManager->flush();

        return $this->json(Response::HTTP_OK);
    }

    #[Route('/api/item/{itemId}', methods: ['DELETE'])]
    public function Delete(ManagerRegistry $doctrine, string $itemId): Response
    {

        $entityManager = $doctrine->getManager();

        $item = $entityManager->getRepository(ReportItems::class)->find($itemId);

        if (!$item) {
            throw $this->createNotFoundException(
                'Item not found'
            );
        }
        $entityManager->remove($item);

        $entityManager->flush();

        return $this->json(Response::HTTP_OK);
    }
}
