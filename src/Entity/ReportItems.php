<?php

namespace App\Entity;

use App\Application\Model\Request\ReportItemsModel;
use App\Repository\ReportItemsRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid as RamseyUuid;

#[ORM\Entity(repositoryClass: ReportItemsRepository::class)]
class ReportItems
{
    #[ORM\Id]
    #[ORM\Column(length: 36)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private string $id;

    #[ORM\ManyToOne(inversedBy: 'reportItems')]
    private ?Report $report = null;

    #[ORM\Column]
    private float $sum;

    #[ORM\Column]
    private string $address;

    #[ORM\Column(name: 'createAt', type: 'datetime', nullable: false)]
    private DateTime $createAt;

    #[ORM\Column(name: 'updateAt', type: 'datetime', nullable: false)]
    private DateTime $updateAt;

    public function __construct(?ReportItemsModel $data = null)

    {
        $this->id = RamseyUuid::uuid4()->toString();
        if ($data) {
            $this->address = $data->getAddress();
            $this->sum = $data->getSum();
        }

        $this->createAt = new DateTime();
        $this->updateAt = new DateTime();
    }

    public function getId(): string
    {
        return $this->id;
    }


    public function getReport(): ?Report
    {
        return $this->report;
    }

    public function setReport(?Report $report): static
    {
        $this->report = $report;

        return $this;
    }

    public function getSum(): ?float
    {
        return $this->sum;
    }

    public function setSum(float $sum): static
    {
        $this->sum = $sum;

        return $this;
    }
    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCreateAt(): DateTime
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdateAt(): DateTime
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }
}
