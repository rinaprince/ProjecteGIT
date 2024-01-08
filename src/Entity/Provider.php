<?php

namespace App\Entity;

use App\Repository\ProviderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProviderRepository::class)]
class Provider
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    private ?string $phone = null;

    #[ORM\Column(length: 20)]
    private ?string $dni = null;

    #[ORM\Column(length: 20)]
    private ?string $cif = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $bankTitle = null;

    #[ORM\Column(length: 20)]
    private ?string $managerNif = null;

    #[ORM\Column(length: 255)]
    private ?string $LOPDdoc = null;

    #[ORM\Column(length: 255)]
    private ?string $constitutionArticle = null;

    #[ORM\OneToMany(mappedBy: 'provider', targetEntity: Vehicle::class, orphanRemoval: true)]
    private Collection $vehicles;

    public function __construct()
    {
        $this->vehicles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): static
    {
        $this->dni = $dni;

        return $this;
    }

    public function getCif(): ?string
    {
        return $this->cif;
    }

    public function setCif(string $cif): static
    {
        $this->cif = $cif;

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

    public function getBankTitle(): ?string
    {
        return $this->bankTitle;
    }

    public function setBankTitle(string $bankTitle): static
    {
        $this->bankTitle = $bankTitle;

        return $this;
    }

    public function getManagerNif(): ?string
    {
        return $this->managerNif;
    }

    public function setManagerNif(string $managerNif): static
    {
        $this->managerNif = $managerNif;

        return $this;
    }

    public function getLOPDdoc(): ?string
    {
        return $this->LOPDdoc;
    }

    public function setLOPDdoc(string $LOPDdoc): static
    {
        $this->LOPDdoc = $LOPDdoc;

        return $this;
    }

    public function getConstitutionArticle(): ?string
    {
        return $this->constitutionArticle;
    }

    public function setConstitutionArticle(string $constitutionArticle): static
    {
        $this->constitutionArticle = $constitutionArticle;

        return $this;
    }

    /**
     * @return Collection<int, Vehicle>
     */
    public function getVehicles(): Collection
    {
        return $this->vehicles;
    }

    public function addVehicle(Vehicle $vehicle): static
    {
        if (!$this->vehicles->contains($vehicle)) {
            $this->vehicles->add($vehicle);
            $vehicle->setProvider($this);
        }

        return $this;
    }

    public function removeVehicle(Vehicle $vehicle): static
    {
        if ($this->vehicles->removeElement($vehicle)) {
            // set the owning side to null (unless already changed)
            if ($vehicle->getProvider() === $this) {
                $vehicle->setProvider(null);
            }
        }

        return $this;
    }
}
