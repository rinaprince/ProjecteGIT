<?php

namespace App\Entity;

use App\Repository\ProviderRepository;
use App\Validator\Dni;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProviderRepository::class)]
#[Vich\Uploadable]
class Provider implements  JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Type(type: 'string')]
    #[Assert\NotBlank]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    #[Assert\Length(max: 9)]
    #[Assert\NotBlank]
    private ?string $phone = null;

    #[ORM\Column(length: 20)]
    #[Assert\Length(max: 9)]
    #[Assert\NotBlank]
    #[Dni]
    private ?string $dni = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 9)]
    private ?string $cif = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $address = null;

    #[Assert\NotBlank]
    #[Assert\File(mimeTypes: "application/pdf" )]
    #[Vich\UploadableField(mapping: 'providers_documents', fileNameProperty: 'bankTitle')]
    private ?File $bankTitleFile = null;

    #[ORM\Column(length: 255)]
    private ?string $bankTitle = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 20)]
    #[Assert\Length(max: 9)]
    #[Dni]
    private ?string $managerNif = null;

    #[Assert\NotBlank]
    #[Assert\File(mimeTypes: "application/pdf" )]
    #[Vich\UploadableField(mapping: 'providers_documents', fileNameProperty: 'LOPDdoc')]
    private ?File $LOPDdocFile = null;

    #[ORM\Column(length: 255)]
    private ?string $LOPDdoc = null;

    #[Assert\File(mimeTypes: "application/pdf" )]
    #[Assert\NotBlank]
    #[Vich\UploadableField(mapping: 'providers_documents', fileNameProperty: 'constitutionArticle')]
    private ?File $constitutionArticleFile = null;

    #[ORM\Column(length: 255)]
    private ?string $constitutionArticle = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'provider', targetEntity: Vehicle::class, orphanRemoval: true)]
    private Collection $vehicles;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $businessName = null;

    #[ORM\Column]
    private ?bool $discharge = null;

    public function __construct()
    {
        $this->vehicles = new ArrayCollection();
    }

    public function setBankTitleFile(?File $file = null): void
    {
        $this->bankTitleFile = $file;

        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getBankTitleFile(): ?File
    {
        return $this->bankTitleFile;
    }

    public function setLOPDdocFile(?File $file = null): void
    {
        $this->LOPDdocFile = $file;

        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getLOPDdocFile(): ?File
    {
        return $this->LOPDdocFile;
    }

    public function setConstitutionArticleFile(?File $file = null): void
    {
        $this->constitutionArticleFile = $file;

        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getConstitutionArticleFile(): ?File
    {
        return $this->constitutionArticleFile;
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

    public function setBankTitle(?string $bankTitle): static
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

    public function setLOPDdoc(?string $LOPDdoc): static
    {
        $this->LOPDdoc = $LOPDdoc;

        return $this;
    }

    public function getConstitutionArticle(): ?string
    {
        return $this->constitutionArticle;
    }

    public function setConstitutionArticle(?string $constitutionArticle): static
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

    public function getBusinessName(): ?string
    {
        return $this->businessName;
    }

    public function setBusinessName(string $businessName): static
    {
        $this->businessName = $businessName;

        return $this;
    }

    function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'businessName' => $this->businessName,
            'phone' => $this->phone,
            'dni' => $this->dni,
            'cif' => $this->cif,
            'address' => $this->address,
            'bankTitle' => $this->bankTitle,
            'managerNif' => $this->managerNif,
            'LOPDdoc' => $this->LOPDdoc,
            'constitutionArticle' => $this->constitutionArticle
        ];
    }

    public function isDischarge(): ?bool
    {
        return $this->discharge;
    }

    public function setDischarge(bool $discharge): static
    {
        $this->discharge = $discharge ?? false;

        return $this;
    }


}