<?php

namespace App\Controller\api\Details;

use App\Application\Model\Response\ReportResponseModel;
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

class DetailsController extends AbstractController
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

    #[Route('/api/details/{year}/{month}', methods: ['GET'])]
    public function loadDataUser(
        Request $request,
        ManagerRegistry $doctrine,
        string $year,
        string $month
    ): Response {

        $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());

        $user = $doctrine->getRepository(User::class)->findOneBy(["email" => $decodedJwtToken["email"]]);

        $reportsItems = $doctrine->getRepository(ReportItems::class)->findAllByMonth($year . "-" . $month, $user->getCompany());

        for ($i = 1; $i <= 31; $i++) {
            $amount[$i] = [];
        }

        foreach ($reportsItems as $item) {
            $dateTime = $item->getReport()->getCreateAt();
            $day = (int)$dateTime->format("d");
            
            $amount[$day][] = $item;
        }

        $details = [];

        foreach ($amount as $key=>$item) {
            $sum = 0;
            foreach ($item as $value) {
                 $sum += $value->getSum();
            }
            $details[$key] = $sum;
        }

        return $this->json(["data" => $details]);
    }
}
