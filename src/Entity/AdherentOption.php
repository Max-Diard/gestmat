<?php

namespace App\Entity;

use App\Repository\AdherentOptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdherentOptionRepository::class)
 */
class AdherentOption
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
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Adherent::class, mappedBy="status_matrimoniale")
     */
    private $adherents;

    /**
     * @ORM\OneToMany(targetEntity=Adherent::class, mappedBy="status_meet")
     */
    private $adhrents_status_meet;

    /**
     * @ORM\OneToMany(targetEntity=Adherent::class, mappedBy="instruction")
     */
    private $instruction;

    /**
     * @ORM\OneToMany(targetEntity=Adherent::class, mappedBy="lodging")
     */
    private $lodging;

    /**
     * @ORM\OneToMany(targetEntity=Adherent::class, mappedBy="smoking")
     */
    private $smoking;

    /**
     * @ORM\OneToMany(targetEntity=Adherent::class, mappedBy="hair")
     */
    private $hair;

    /**
     * @ORM\OneToMany(targetEntity=Adherent::class, mappedBy="zodiaque")
     */
    private $zodiaque;

    /**
     * @ORM\OneToMany(targetEntity=Adherent::class, mappedBy="eyes")
     */
    private $eyes;

    /**
     * @ORM\OneToMany(targetEntity=Adherent::class, mappedBy="genre")
     */
    private $genre;

    /**
     * @ORM\OneToMany(targetEntity=Adherent::class, mappedBy="preference_contact")
     */
    private $preference_contact;

    /**
     * @ORM\OneToMany(targetEntity=Adherent::class, mappedBy="search_instruction")
     */
    private $search_instruction;

    /**
     * @ORM\OneToMany(targetEntity=Adherent::class, mappedBy="owner")
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity=Meet::class, mappedBy="status_meet_woman")
     */
    private $status_meet_woman;

    /**
     * @ORM\OneToMany(targetEntity=Meet::class, mappedBy="status_meet_man")
     */
    private $status_meet_man;

    /**
     * @ORM\OneToMany(targetEntity=Adherent::class, mappedBy="type_payment")
     */
    private $type_payment;

    public function __construct()
    {
        $this->adherents = new ArrayCollection();
        $this->adhrents_status_meet = new ArrayCollection();
        $this->instruction = new ArrayCollection();
        $this->lodging = new ArrayCollection();
        $this->smoking = new ArrayCollection();
        $this->hair = new ArrayCollection();
        $this->zodiaque = new ArrayCollection();
        $this->eyes = new ArrayCollection();
        $this->genre = new ArrayCollection();
        $this->preference_contact = new ArrayCollection();
        $this->search_instruction = new ArrayCollection();
        $this->owner = new ArrayCollection();
        $this->type_payment = new ArrayCollection();
        $this->status_meet_woman = new ArrayCollection();
        $this->status_meet_man = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Adherent[]
     */
    public function getAdherentsStatusMatrimoniale(): Collection
    {
        return $this->adherents_status_matrimoniale;
    }

    public function addAdherentStatusMatrimoniale(Adherent $adherentStatusMatriomoniale): self
    {
        if (!$this->adherents_status_matrimoniale->contains($adherentStatusMatriomoniale)) {
            $this->adherents_status_matrimoniale[] = $adherentStatusMatriomoniale;
            $adherentStatusMatriomoniale->setStatusMatrimoniale($this);
        }

        return $this;
    }

    public function removeAdherentStatusMatrimoniale(Adherent $adherentStatusMatriomoniale): self
    {
        if ($this->adherents_status_matrimoniale->removeElement($adherentStatusMatriomoniale)) {
            // set the owning side to null (unless already changed)
            if ($adherentStatusMatriomoniale->getStatusMatrimoniale() === $this) {
                $adherentStatusMatriomoniale->setStatusMatrimoniale(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Adherent[]
     */
    public function getAdhrentsStatusMeet(): Collection
    {
        return $this->adhrents_status_meet;
    }

    public function addAdhrentsStatusMeet(Adherent $adhrentsStatusMeet): self
    {
        if (!$this->adhrents_status_meet->contains($adhrentsStatusMeet)) {
            $this->adhrents_status_meet[] = $adhrentsStatusMeet;
            $adhrentsStatusMeet->setStatusMeet($this);
        }

        return $this;
    }

    public function removeAdhrentsStatusMeet(Adherent $adhrentsStatusMeet): self
    {
        if ($this->adhrents_status_meet->removeElement($adhrentsStatusMeet)) {
            // set the owning side to null (unless already changed)
            if ($adhrentsStatusMeet->getStatusMeet() === $this) {
                $adhrentsStatusMeet->setStatusMeet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Adherent[]
     */
    public function getInstruction(): Collection
    {
        return $this->instruction;
    }

    public function addInstruction(Adherent $instruction): self
    {
        if (!$this->instruction->contains($instruction)) {
            $this->instruction[] = $instruction;
            $instruction->setInstruction($this);
        }

        return $this;
    }

    public function removeInstruction(Adherent $instruction): self
    {
        if ($this->instruction->removeElement($instruction)) {
            // set the owning side to null (unless already changed)
            if ($instruction->getInstruction() === $this) {
                $instruction->setInstruction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Adherent[]
     */
    public function getLodging(): Collection
    {
        return $this->lodging;
    }

    public function addLodging(Adherent $lodging): self
    {
        if (!$this->lodging->contains($lodging)) {
            $this->lodging[] = $lodging;
            $lodging->setLodging($this);
        }

        return $this;
    }

    public function removeLodging(Adherent $lodging): self
    {
        if ($this->lodging->removeElement($lodging)) {
            // set the owning side to null (unless already changed)
            if ($lodging->getLodging() === $this) {
                $lodging->setLodging(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Adherent[]
     */
    public function getSmoking(): Collection
    {
        return $this->smoking;
    }

    public function addSmoking(Adherent $smoking): self
    {
        if (!$this->smoking->contains($smoking)) {
            $this->smoking[] = $smoking;
            $smoking->setSmoking($this);
        }

        return $this;
    }

    public function removeSmoking(Adherent $smoking): self
    {
        if ($this->smoking->removeElement($smoking)) {
            // set the owning side to null (unless already changed)
            if ($smoking->getSmoking() === $this) {
                $smoking->setSmoking(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Adherent[]
     */
    public function getHair(): Collection
    {
        return $this->hair;
    }

    public function addHair(Adherent $hair): self
    {
        if (!$this->hair->contains($hair)) {
            $this->hair[] = $hair;
            $hair->setHair($this);
        }

        return $this;
    }

    public function removeHair(Adherent $hair): self
    {
        if ($this->hair->removeElement($hair)) {
            // set the owning side to null (unless already changed)
            if ($hair->getHair() === $this) {
                $hair->setHair(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Adherent[]
     */
    public function getZodiaque(): Collection
    {
        return $this->zodiaque;
    }

    public function addZodiaque(Adherent $zodiaque): self
    {
        if (!$this->zodiaque->contains($zodiaque)) {
            $this->zodiaque[] = $zodiaque;
            $zodiaque->setZodiaque($this);
        }

        return $this;
    }

    public function removeZodiaque(Adherent $zodiaque): self
    {
        if ($this->zodiaque->removeElement($zodiaque)) {
            // set the owning side to null (unless already changed)
            if ($zodiaque->getZodiaque() === $this) {
                $zodiaque->setZodiaque(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Adherent[]
     */
    public function getEyes(): Collection
    {
        return $this->eyes;
    }

    public function addEye(Adherent $eye): self
    {
        if (!$this->eyes->contains($eye)) {
            $this->eyes[] = $eye;
            $eye->setEyes($this);
        }

        return $this;
    }

    public function removeEye(Adherent $eye): self
    {
        if ($this->eyes->removeElement($eye)) {
            // set the owning side to null (unless already changed)
            if ($eye->getEyes() === $this) {
                $eye->setEyes(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Adherent[]
     */
    public function getGenre(): Collection
    {
        return $this->genre;
    }

    public function addGenre(Adherent $genre): self
    {
        if (!$this->genre->contains($genre)) {
            $this->genre[] = $genre;
            $genre->setGenre($this);
        }

        return $this;
    }

    public function removeGenre(Adherent $genre): self
    {
        if ($this->genre->removeElement($genre)) {
            // set the owning side to null (unless already changed)
            if ($genre->getGenre() === $this) {
                $genre->setGenre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Adherent[]
     */
    public function getPreferenceContact(): Collection
    {
        return $this->preference_contact;
    }

    public function addPreferenceContact(Adherent $preferenceContact): self
    {
        if (!$this->preference_contact->contains($preferenceContact)) {
            $this->preference_contact[] = $preferenceContact;
            $preferenceContact->setPreferenceContact($this);
        }

        return $this;
    }

    public function removePreferenceContact(Adherent $preferenceContact): self
    {
        if ($this->preference_contact->removeElement($preferenceContact)) {
            // set the owning side to null (unless already changed)
            if ($preferenceContact->getPreferenceContact() === $this) {
                $preferenceContact->setPreferenceContact(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Adherent[]
     */
    public function getSearchInstruction(): Collection
    {
        return $this->search_instruction;
    }

    public function addSearchInstruction(Adherent $searchInstruction): self
    {
        if (!$this->search_instruction->contains($searchInstruction)) {
            $this->search_instruction[] = $searchInstruction;
            $searchInstruction->setSearchInstruction($this);
        }

        return $this;
    }

    public function removeSearchInstruction(Adherent $searchInstruction): self
    {
        if ($this->search_instruction->removeElement($searchInstruction)) {
            // set the owning side to null (unless already changed)
            if ($searchInstruction->getSearchInstruction() === $this) {
                $searchInstruction->setSearchInstruction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Adherent[]
     */
    public function getOwner(): Collection
    {
        return $this->owner;
    }

    public function addOwner(Adherent $owner): self
    {
        if (!$this->owner->contains($owner)) {
            $this->owner[] = $owner;
            $owner->setOwner($this);
        }

        return $this;
    }

    public function removeOwner(Adherent $owner): self
    {
        if ($this->owner->removeElement($owner)) {
            // set the owning side to null (unless already changed)
            if ($owner->getOwner() === $this) {
                $owner->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Meet[]
     */
    public function getStatusMeetWoman(): Collection
    {
        return $this->status_meet_woman;
    }

    public function addStatusMeetWoman(Meet $statusMeetWoman): self
    {
        if (!$this->status_meet_woman->contains($statusMeetWoman)) {
            $this->status_meet_woman[] = $statusMeetWoman;
            $statusMeetWoman->setStatusMeetWoman($this);
        }

        return $this;
    }

    public function removeStatusMeetWoman(Meet $statusMeetWoman): self
    {
        if ($this->status_meet_woman->removeElement($statusMeetWoman)) {
            // set the owning side to null (unless already changed)
            if ($statusMeetWoman->getStatusMeetWoman() === $this) {
                $statusMeetWoman->setStatusMeetWoman(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Meet[]
     */
    public function getStatusMeetMan(): Collection
    {
        return $this->status_meet_man;
    }

    public function addStatusMeetMan(Meet $statusMeetMan): self
    {
        if (!$this->status_meet_man->contains($statusMeetMan)) {
            $this->status_meet_man[] = $statusMeetMan;
            $statusMeetMan->setStatusMeetMan($this);
        }

        return $this;
    }

    public function removeStatusMeetMan(Meet $statusMeetMan): self
    {
        if ($this->status_meet_man->removeElement($statusMeetMan)) {
            // set the owning side to null (unless already changed)
            if ($statusMeetMan->getStatusMeetMan() === $this) {
                $statusMeetMan->setStatusMeetMan(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Adherent[]
     */
    public function getTypePayment(): Collection
    {
        return $this->type_payment;
    }

    public function addTypePayment(Adherent $typePayment): self
    {
        if (!$this->type_payment->contains($typePayment)) {
            $this->type_payment[] = $typePayment;
            $typePayment->setTypePayment($this);
        }

        return $this;
    }

    public function removeTypePayment(Adherent $typePayment): self
    {
        if ($this->type_payment->removeElement($typePayment)) {
            // set the owning side to null (unless already changed)
            if ($typePayment->getTypePayment() === $this) {
                $typePayment->setTypePayment(null);
            }
        }

        return $this;
    }

}
