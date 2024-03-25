<?php

namespace App\Application\Model\Request;

use App\Application\Ex\RequiredField;
use Symfony\Component\HttpFoundation\Request;


class ReportItemsModel
{
    private string $address;
    private float $sum;


    public function __construct()
    {
    }

    public static function create(Request $request): self
    {

        $data = $request->toArray();

        $reportItem = new self($request);

        $reportItem->address = $data["address"] ?? throw new RequiredField("address");

        $reportItem->sum = $data["sum"] ?? throw new RequiredField("sum");

        return $reportItem;
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
