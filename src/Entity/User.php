<?php

namespace App\Entity;

use App\Application\Model\Request\UserModel;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(length: 36)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private string $id;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private string $password;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column(length: 100)]
    private string $sername;

    #[ORM\Column(length: 100)]
    private string $patronymic;

    #[ORM\Column(length: 15, nullable: false)]
    private string $phone;

    #[ORM\Column(name: 'dateCreated', type: 'datetime', nullable: false)]
    private DateTime $dateCreated;

    #[ORM\Column(name: 'dateUpdate', type: 'datetime', nullable: false)]
    private DateTime $dateUpdate;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Company $company = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Report::class)]
    private Collection $reports;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ReportItems::class)]
    private Collection $reportItems;



    public function __construct(

        ?UserModel $data = null,

    ) {
        $this->id = RamseyUuid::uuid4()->toString();

        $this->dateCreated = new DateTime();
        $this->dateUpdate = new DateTime();
        $this->reports = new ArrayCollection();
        $this->reportItems = new ArrayCollection();
        if ($data) {
            $this->name = $data->getName();
            $this->sername = $data->getSername();
            $this->patronymic = $data->getPatronymic();
            $this->email = $data->getEmail();
            $this->phone = $data->getPhone();
            $this->password = $data->getPassword();
        }
    }


    public function fixtures()
    {
        $this->id = RamseyUuid::uuid4()->toString();

        $this->dateCreated = new DateTime();
        $this->dateUpdate = new DateTime();
        $this->reports = new ArrayCollection();
        $this->reportItems = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSername(): string
    {
        return $this->sername;
    }

    public function setSername(string $sername): static
    {
        $this->sername = $sername;

        return $this;
    }

    public function getPatronymic(): string
    {
        return $this->patronymic;
    }

    public function setPatronymic(string $patronymic): static
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    public function getDateCreated(): DateTime
    {
        return $this->dateCreated;
    }

    public function setCreateAt(\DateTime $dateCreated): static
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateUpdate(): DateTime
    {
        return $this->dateUpdate;
    }

    public function setDateUpdate(DateTime $dateUpdate): static
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $Company): static
    {
        $this->company = $Company;

        return $this;
    }

    /**
     * @return Collection<int, Report>
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): static
    {
        if (!$this->reports->contains($report)) {
            $this->reports->add($report);
            $report->setUser($this);
        }

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
            $reportItem->setUser($this);
        }

        return $this;
    }

    public function removeReportItem(ReportItems $reportItem): static
    {
        if ($this->reportItems->removeElement($reportItem)) {
            // set the owning side to null (unless already changed)
            if ($reportItem->getUser() === $this) {
                $reportItem->setUser(null);
            }
        }

        return $this;
    }
}
