<?php

namespace App\Entity;

use App\Repository\MeetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MeetRepository::class)
 */
class Meet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("adherent:read")
     * @Groups("meet:read")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="adherent_woman")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("adherent:read")
     * @Groups("meet:read")
     */
    private $adherent_woman;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="adherent_man")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("adherent:read")
     * @Groups("meet:read")
     */
    private $adherent_man;
    
    /**
     * @ORM\Column(type="date")
     * @Groups("adherent:read")
     * @Groups("meet:read")
     */
    private $startedAt;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups("adherent:read")
     * @Groups("meet:read")
     */
    private $returnAt_woman;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups("adherent:read")
     * @Groups("meet:read")
     */
    private $returnAt_man;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("adherent:read")
     * @Groups("meet:read")
     */
    private $comments_woman;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("adherent:read")
     * @Groups("meet:read")
     */
    private $comments_man;

    /**
     * @ORM\ManyToOne(targetEntity=AdherentOption::class, inversedBy="status_meet_woman")
     * @Groups("meet:read")
     */
    private $status_meet_woman;

    /**
     * @ORM\ManyToOne(targetEntity=AdherentOption::class, inversedBy="status_meet_man")
     * @Groups("meet:read")
     */
    private $status_meet_man;

    /**
     * @ORM\ManyToOne(targetEntity=AdherentOption::class, inversedBy="meets")
     * @Groups("meet:read")
     */
    private $actionMeetWoman;

    /**
     * @ORM\ManyToOne(targetEntity=AdherentOption::class, inversedBy="meets")
     * @Groups("meet:read")
     */
    private $actionMeetMan;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getReturnAtWoman(): ?\DateTimeInterface
    {
        return $this->returnAt_woman;
    }

    public function setReturnAtWoman(?\DateTimeInterface $returnAt_woman): self
    {
        $this->returnAt_woman = $returnAt_woman;

        return $this;
    }

    public function getReturnAtMan(): ?\DateTimeInterface
    {
        return $this->returnAt_man;
    }

    public function setReturnAtMan(?\DateTimeInterface $returnAt_man): self
    {
        $this->returnAt_man = $returnAt_man;

        return $this;
    }

    public function getCommentsWoman(): ?string
    {
        return $this->comments_woman;
    }

    public function setCommentsWoman(?string $comments_woman): self
    {
        $this->comments_woman = $comments_woman;

        return $this;
    }

    public function getCommentsMan(): ?string
    {
        return $this->comments_man;
    }

    public function setCommentsMan(?string $comments_man): self
    {
        $this->comments_man = $comments_man;

        return $this;
    }

    public function getStatusMeetWoman(): ?AdherentOption
    {
        return $this->status_meet_woman;
    }

    public function setStatusMeetWoman(?AdherentOption $status_meet_woman): self
    {
        $this->status_meet_woman = $status_meet_woman;

        return $this;
    }

    public function getStatusMeetMan(): ?AdherentOption
    {
        return $this->status_meet_man;
    }

    public function setStatusMeetMan(?AdherentOption $status_meet_man): self
    {
        $this->status_meet_man = $status_meet_man;

        return $this;
    }

    public function getAdherentWoman(): ?Adherent
    {
        return $this->adherent_woman;
    }

    public function setAdherentWoman(?Adherent $adherent_woman): self
    {
        $this->adherent_woman = $adherent_woman;

        return $this;
    }

    public function getAdherentMan(): ?Adherent
    {
        return $this->adherent_man;
    }

    public function setAdherentMan(?Adherent $adherent_man): self
    {
        $this->adherent_man = $adherent_man;

        return $this;
    }

    public function getActionMeetWoman(): ?AdherentOption
    {
        return $this->actionMeetWoman;
    }

    public function setActionMeetWoman(?AdherentOption $actionMeetWoman): self
    {
        $this->actionMeetWoman = $actionMeetWoman;

        return $this;
    }

    public function getActionMeetMan(): ?AdherentOption
    {
        return $this->actionMeetMan;
    }

    public function setActionMeetMan(?AdherentOption $actionMeetMan): self
    {
        $this->actionMeetMan = $actionMeetMan;

        return $this;
    }
}
