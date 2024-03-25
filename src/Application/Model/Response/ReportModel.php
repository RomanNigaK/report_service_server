<?php

namespace App\Application\Model\Response;

use App\Entity\Report;
use App\Entity\ReportItems;
use DateTime;

class ReportResponseModel
{

    public string $id;
    public string $author;
    public DateTime $date;
    public int $sum;
    public int $ordering;
    public int $amountRow;

    public function __construct()
    {
    }

    public static function create(Report $report, mixed $list=null): self
    {

        $response = new self($report);
        $response->id = $report->getId();
        $author = $report->getUser()->getSername() . " " . $report->getUser()->getName() . " " . $report->getUser()->getPatronymic();
        $response->author = $author;
        $response->ordering = $report->getOrdering();
        $response->date = $report->getCreateAt();
        $amount = 0;
        $s = 0;
        if ($list) {
            foreach ($list as $i) {
                $amount = ++$amount;
                $s = $s + $i->getSum();
            }
        }


        $response->amountRow = $amount;
        $response->sum = $s;
        return $response;
    }
}
