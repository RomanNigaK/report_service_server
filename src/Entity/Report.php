<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid as RamseyUuid;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
class Report
{
    #[ORM\Id]
    #[ORM\Column(length: 36)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private string $id;

    #[ORM\Column(name: 'createAt', type: 'datetime', nullable: false)]
    private DateTime $createAt;

    #[ORM\Column(name: 'updateAt', type: 'datetime', nullable: false)]
    private DateTime $updateAt;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    private User $user;

    #[ORM\OneToMany(mappedBy: 'report', targetEntity: ReportItems::class)]
    private Collection $reportItems;

    #[ORM\Column]
    private int $ordering;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    private ?Company $company = null;

    public function __construct(?DateTime $dt=null)

    {
        $this->id = RamseyUuid::uuid4()->toString();
        $this->createAt = $dt ?? new DateTime();
        $this->updateAt = $dt ?? new DateTime();
        $this->reportItems = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCreateAt(): DateTime
    {
        return $this->createAt;
    }

    public function setCreateAt(DateTime $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdateAt(): DateTime
    {
        return $this->updateAt;
    }

    public function setUpdateAt(DateTime $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, ReportItems>
     */
    public function getReportItems(): Collection
    {
        return $this->reportItems;
    }

    public function addReportItem(ReportItems $reportItem): static
    {
        if (!$this->reportItems->contains($reportItem)) {
            $this->reportItems->add($reportItem);
            $reportItem->setReport($this);
        }

        return $this;
    }

    public function removeReportItem(ReportItems $reportItem): static
    {
        if ($this->reportItems->removeElement($reportItem)) {
            // set the owning side to null (unless already changed)
            if ($reportItem->getReport() === $this) {
                $reportItem->setReport(null);
            }
        }

        return $this;
    }

    public function getOrdering(): int
    {
        return $this->ordering;
    }

    public function setOrdering(int $ordering): static
    {
        $this->ordering = $ordering;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }
}
