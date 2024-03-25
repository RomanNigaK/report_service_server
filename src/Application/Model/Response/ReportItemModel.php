<?php

namespace App\Application\Model\Response;

use App\Entity\ReportItems;


class ReportItemResponseModel
{
    public string $id;
    public string $address;
    public string $authorId;
    public float $sum;


    public function __construct()
    {
    }

    public static function create(ReportItems $reportItem, string $authorId): self
    {

        $response = new self($reportItem);
        $response->id = $reportItem->getId();
        $response->address = $reportItem->getAddress();
        $response->sum = $reportItem->getSum();
        $response->authorId = $authorId;

        return $response;
    }
}
