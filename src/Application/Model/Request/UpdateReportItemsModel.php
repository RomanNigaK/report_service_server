<?php

namespace App\Application\Model\Request;

use App\Entity\ReportItems;
use Symfony\Component\HttpFoundation\Request;


class UpdateReportItemsModel
{
    private string $address;
    private float $sum;


    public function __construct()
    {
    }

    public static function create(Request $request, ReportItems $item): ReportItems
    {

        $data = $request->toArray();

        $item->setAddress($data["address"] ?? $item->getAddress());
        $item->setSum($data["sum"] ?? $item->getSum());

        return $item;
    }

    public function getAddress()
    {
        return $this->address;
    }
    public function getSum()
    {
        return $this->sum;
    }
}
