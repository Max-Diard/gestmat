<?php

namespace App\Entity;

use App\Repository\AgenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AgenceRepository::class)
 */
class Agence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $phone_number;

    /**
     * @ORM\Column(type="date")
     */
    private $startedAt;

    /**
     * @ORM\Column(type="date")
     */
    private $endingAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $link_picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address_street;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address_street2;

    /**
     * @ORM\Column(type="integer")
     */
    private $address_zip_postal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address_town;

    /**
     * @ORM\OneToMany(targetEntity=Adherent::class, mappedBy="agence", fetch="EAGER", orphanRemoval=true)
     */
    private $adherents;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="agence", fetch="EAGER")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity=Agence::class, inversedBy="droit_agence", fetch="EAGER")
     */
    private $droit_agence;

    public function __construct()
    {
        $this->adherents = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->droit_agence = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeInterface $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndingAt(): ?\DateTimeInterface
    {
        return $this->endingAt;
    }

    public function setEndingAt(\DateTimeInterface $endingAt): self
    {
        $this->endingAt = $endingAt;

        return $this;
    }

    public function getLinkPicture()
    {
        return $this->link_picture;
    }

    public function setLinkPicture($link_picture): self
    {
        $this->link_picture = $link_picture;

        return $this;
    }

    public function getAddressStreet(): ?string
    {
        return $this->address_street;
    }

    public function setAddressStreet(string $address_street): self
    {
        $this->address_street = $address_street;

        return $this;
    }

    public function getAddressStreet2(): ?string
    {
        return $this->address_street2;
    }

    public function setAddressStreet2(?string $address_street2): self
    {
        $this->address_street2 = $address_street2;

        return $this;
    }

    public function getAddressZipPostal(): ?int
    {
        return $this->address_zip_postal;
    }

    public function setAddressZipPostal(int $address_zip_postal): self
    {
        $this->address_zip_postal = $address_zip_postal;

        return $this;
    }

    public function getAddressTown(): ?string
    {
        return $this->address_town;
    }

    public function setAddressTown(string $address_town): self
    {
        $this->address_town = $address_town;

        return $this;
    }

    /**
     * @return Collection|Adherent[]
     */
    public function getAdherents(): Collection
    {
        return $this->adherents;
    }

    public function addAdherent(Adherent $adherent): self
    {
        if (!$this->adherents->contains($adherent)) {
            $this->adherents[] = $adherent;
            $adherent->setAgence($this);
        }

        return $this;
    }

    public function removeAdherent(Adherent $adherent): self
    {
        if ($this->adherents->removeElement($adherent)) {
            // set the owning side to null (unless already changed)
            if ($adherent->getAgence() === $this) {
                $adherent->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addAgence($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeAgence($this);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getDroitAgence(): Collection
    {
        return $this->droit_agence;
    }

    public function addDroitAgence(self $droitAgence): self
    {
        if (!$this->droit_agence->contains($droitAgence)) {
            $this->droit_agence[] = $droitAgence;
        }

        return $this;
    }

    public function removeDroitAgence(self $droitAgence): self
    {
        $this->droit_agence->removeElement($droitAgence);

        return $this;
    }
}
