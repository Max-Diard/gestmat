<?php

namespace App\Entity;

use App\Repository\AdherentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdherentRepository::class)
 */
class Adherent
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
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="date")
     */
    private $birthdate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments1;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments2;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone_mobile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone_home;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone_work;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone_comments;

    /**
     * @ORM\Column(type="text")
     */
    private $profession;

    /**
     * @ORM\Column(type="float")
     */
    private $size;

    /**
     * @ORM\Column(type="float")
     */
    private $weight;

    /**
     * @ORM\Column(type="boolean")
     */
    private $permis;

    /**
     * @ORM\Column(type="boolean")
     */
    private $car;

    /**
     * @ORM\Column(type="boolean")
     */
    private $announcement_publish;

    /**
     * @ORM\Column(type="text")
     */
    private $announcement_presentation;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $announcement_free;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $announcement_date_free;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $announcement_newspaper;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $announcement_date_newspaper;

    /**
     * @ORM\Column(type="boolean")
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $link_picture;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $link_contrat;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $link_information;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $link_picture_announcement;

    /**
     * @ORM\Column(type="text")
     */
    private $address_street;

    /**
     * @ORM\Column(type="text", nullable=true)
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
     * @ORM\Column(type="integer")
     */
    private $child_girl;

    /**
     * @ORM\Column(type="integer")
     */
    private $child_boy;

    /**
     * @ORM\Column(type="integer")
     */
    private $child_dependent_girl;

    /**
     * @ORM\Column(type="integer")
     */
    private $child_dependent_boy;

    /**
     * @ORM\Column(type="integer")
     */
    private $search_age_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $search_age_max;

    /**
     * @ORM\Column(type="boolean")
     */
    private $search_single;

    /**
     * @ORM\Column(type="boolean")
     */
    private $search_divorced;

    /**
     * @ORM\Column(type="boolean")
     */
    private $search_windower;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $search_profession;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $search_region;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $search_more;

    /**
     * @ORM\Column(type="boolean")
     */
    private $search_accept_children;

    /**
     * @ORM\Column(type="integer")
     */
    private $search_number_accept_children;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getComments1(): ?string
    {
        return $this->comments1;
    }

    public function setComments1(?string $comments1): self
    {
        $this->comments1 = $comments1;

        return $this;
    }

    public function getComments2(): ?string
    {
        return $this->comments2;
    }

    public function setComments2(?string $comments2): self
    {
        $this->comments2 = $comments2;

        return $this;
    }

    public function getComments3(): ?string
    {
        return $this->comments3;
    }

    public function setComments3(?string $comments3): self
    {
        $this->comments3 = $comments3;

        return $this;
    }

    public function getPhoneMobile(): ?string
    {
        return $this->phone_mobile;
    }

    public function setPhoneMobile(string $phone_mobile): self
    {
        $this->phone_mobile = $phone_mobile;

        return $this;
    }

    public function getPhoneHome(): ?string
    {
        return $this->phone_home;
    }

    public function setPhoneHome(?string $phone_home): self
    {
        $this->phone_home = $phone_home;

        return $this;
    }

    public function getPhoneWork(): ?string
    {
        return $this->phone_work;
    }

    public function setPhoneWork(?string $phone_work): self
    {
        $this->phone_work = $phone_work;

        return $this;
    }

    public function getPhoneComments(): ?string
    {
        return $this->phone_comments;
    }

    public function setPhoneComments(?string $phone_comments): self
    {
        $this->phone_comments = $phone_comments;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getSize(): ?float
    {
        return $this->size;
    }

    public function setSize(float $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getPermis(): ?bool
    {
        return $this->permis;
    }

    public function setPermis(bool $permis): self
    {
        $this->permis = $permis;

        return $this;
    }

    public function getCar(): ?bool
    {
        return $this->car;
    }

    public function setCar(bool $car): self
    {
        $this->car = $car;

        return $this;
    }

    public function getAnnouncementPublish(): ?bool
    {
        return $this->announcement_publish;
    }

    public function setAnnouncementPublish(bool $annoucement_publish): self
    {
        $this->annoucement_publish = $annoucement_publish;

        return $this;
    }

    public function getAnnouncementPresentation(): ?string
    {
        return $this->announcement_presentation;
    }

    public function setAnnouncementPresentation(string $announcement_presentation): self
    {
        $this->announcement_presentation = $announcement_presentation;

        return $this;
    }

    public function getAnnouncementFree(): ?string
    {
        return $this->announcement_free;
    }

    public function setAnnouncementFree(?string $announcement_free): self
    {
        $this->announcement_free = $announcement_free;

        return $this;
    }

    public function getAnnouncementDateFree(): ?\DateTimeInterface
    {
        return $this->announcement_date_free;
    }

    public function setAnnouncementDateFree(?\DateTimeInterface $announcement_date_free): self
    {
        $this->announcement_date_free = $announcement_date_free;

        return $this;
    }

    public function getAnnouncementNewspaper(): ?string
    {
        return $this->announcement_newspaper;
    }

    public function setAnnouncementNewspaper(?string $announcement_newspaper): self
    {
        $this->announcement_newspaper = $announcement_newspaper;

        return $this;
    }

    public function getAnnouncementDateNewspaper(): ?\DateTimeInterface
    {
        return $this->announcement_date_newspaper;
    }

    public function setAnnouncementDateNewspaper(?\DateTimeInterface $announcement_date_newspaper): self
    {
        $this->announcement_date_newspaper = $announcement_date_newspaper;

        return $this;
    }

    public function getOwner(): ?bool
    {
        return $this->owner;
    }

    public function setOwner(bool $owner): self
    {
        $this->owner = $owner;

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

    public function getLinkPicture(): ?string
    {
        return $this->link_picture;
    }

    public function setLinkPicture(?string $link_picture): self
    {
        $this->link_picture = $link_picture;

        return $this;
    }

    public function getLinkContrat(): ?string
    {
        return $this->link_contrat;
    }

    public function setLinkContrat(?string $link_contrat): self
    {
        $this->link_contrat = $link_contrat;

        return $this;
    }

    public function getLinkInformation(): ?string
    {
        return $this->link_information;
    }

    public function setLinkInformation(?string $link_information): self
    {
        $this->link_information = $link_information;

        return $this;
    }

    public function getLinkPictureAnnouncement(): ?string
    {
        return $this->link_picture_announcement;
    }

    public function setLinkPictureAnnouncement(?string $link_picture_announcement): self
    {
        $this->link_picture_announcement = $link_picture_announcement;

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

    public function getChildGirl(): ?int
    {
        return $this->child_girl;
    }

    public function setChildGirl(int $child_girl): self
    {
        $this->child_girl = $child_girl;

        return $this;
    }

    public function getChildBoy(): ?int
    {
        return $this->child_boy;
    }

    public function setChildBoy(int $child_boy): self
    {
        $this->child_boy = $child_boy;

        return $this;
    }

    public function getChildDependentGirl(): ?int
    {
        return $this->child_dependent_girl;
    }

    public function setChildDependentGirl(int $child_dependent_girl): self
    {
        $this->child_dependent_girl = $child_dependent_girl;

        return $this;
    }

    public function getChildDependentBoy(): ?int
    {
        return $this->child_dependent_boy;
    }

    public function setChildDependentBoy(int $child_dependent_boy): self
    {
        $this->child_dependent_boy = $child_dependent_boy;

        return $this;
    }

    public function getSearchAgeMin(): ?int
    {
        return $this->search_age_min;
    }

    public function setSearchAgeMin(int $search_age_min): self
    {
        $this->search_age_min = $search_age_min;

        return $this;
    }

    public function getSearchAgeMax(): ?int
    {
        return $this->search_age_max;
    }

    public function setSearchAgeMax(int $search_age_max): self
    {
        $this->search_age_max = $search_age_max;

        return $this;
    }

    public function getSearchSingle(): ?bool
    {
        return $this->search_single;
    }

    public function setSearchSingle(bool $search_single): self
    {
        $this->search_single = $search_single;

        return $this;
    }

    public function getSearchDivorced(): ?bool
    {
        return $this->search_divorced;
    }

    public function setSearchDivorced(bool $search_divorced): self
    {
        $this->search_divorced = $search_divorced;

        return $this;
    }

    public function getSearchWindower(): ?bool
    {
        return $this->search_windower;
    }

    public function setSearchWindower(bool $search_windower): self
    {
        $this->search_windower = $search_windower;

        return $this;
    }

    public function getSearchProfession(): ?string
    {
        return $this->search_profession;
    }

    public function setSearchProfession(?string $search_profession): self
    {
        $this->search_profession = $search_profession;

        return $this;
    }

    public function getSearchRegion(): ?string
    {
        return $this->search_region;
    }

    public function setSearchRegion(?string $search_region): self
    {
        $this->search_region = $search_region;

        return $this;
    }

    public function getSearchMore(): ?string
    {
        return $this->search_more;
    }

    public function setSearchMore(?string $search_more): self
    {
        $this->search_more = $search_more;

        return $this;
    }

    public function getSearchAcceptChildren(): ?bool
    {
        return $this->search_accept_children;
    }

    public function setSearchAcceptChildren(bool $search_accept_children): self
    {
        $this->search_accept_children = $search_accept_children;

        return $this;
    }

    public function getSearchNumberAcceptChildren(): ?int
    {
        return $this->search_number_accept_children;
    }

    public function setSearchNumberAcceptChildren(int $search_number_accept_children): self
    {
        $this->search_number_accept_children = $search_number_accept_children;

        return $this;
    }
}
