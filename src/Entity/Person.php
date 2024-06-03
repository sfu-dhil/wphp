<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'person')]
#[ORM\Index(name: 'person_full_idx', columns: ['last_name', 'first_name', 'title'], flags: ['fulltext'])]
#[ORM\Index(name: 'person_viaf_idx', columns: ['viaf_permalink'], flags: ['fulltext'])]
#[ORM\Index(name: 'person_wikipedia_idx', columns: ['wikipedia_link'], flags: ['fulltext'])]
#[ORM\Index(name: 'person_image_idx', columns: ['image_link'], flags: ['fulltext'])]
#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person extends AbstractEntity {
    final public const MALE = 'M';

    final public const FEMALE = 'F';

    final public const UNKNOWN = 'U';

    final public const TRANS = 'T';

    #[ORM\Column(name: 'last_name', type: 'string', length: 100, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(name: 'first_name', type: 'string', length: 100, nullable: true)]
    private ?string $firstName;

    #[ORM\Column(name: 'title', type: 'string', length: 200, nullable: true)]
    private ?string $title;

    #[ORM\Column(name: 'gender', type: 'string', length: 1, nullable: false, options: ['default' => 'U'])]
    private ?string $gender;

    #[ORM\Column(name: 'dob', type: 'string', length: 20, nullable: true)]
    private ?string $dob = null;

    #[ORM\Column(name: 'dod', type: 'string', length: 20, nullable: true)]
    private ?string $dod = null;

    #[Assert\Url]
    #[ORM\Column(name: 'viaf_permalink', type: 'string', length: 127, nullable: true)]
    private ?string $viafUrl = null;

    #[Assert\Url]
    #[ORM\Column(name: 'wikipedia_link', type: 'string', length: 127, nullable: true)]
    private ?string $wikipediaUrl = null;

    #[Assert\Url]
    #[ORM\Column(name: 'image_link', type: 'string', length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(name: 'notes', type: 'text', nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(name: 'finalcheck', type: 'boolean', nullable: false)]
    private bool $finalcheck = false;

    #[ORM\JoinColumn(name: 'city_id_of_birth', referencedColumnName: 'geonameid')]
    #[ORM\ManyToOne(targetEntity: Geonames::class, inversedBy: 'peopleBorn')]
    private ?Geonames $cityOfBirth = null;

    #[ORM\JoinColumn(name: 'city_id_of_death', referencedColumnName: 'geonameid')]
    #[ORM\ManyToOne(targetEntity: Geonames::class, inversedBy: 'peopleDied')]
    private ?Geonames $cityOfDeath = null;

    /**
     * @var Collection<int,TitleRole>
     */
    #[ORM\OneToMany(targetEntity: TitleRole::class, mappedBy: 'person')]
    private array|Collection $titleRoles;

    /**
     * @var Collection<int,Firm>
     */
    #[ORM\ManyToMany(targetEntity: Firm::class, inversedBy: 'relatedPeople')]
    private array|Collection $relatedFirms;

    public function __construct() {
        parent::__construct();
        $this->gender = self::UNKNOWN;
        $this->titleRoles = new ArrayCollection();
        $this->relatedFirms = new ArrayCollection();
    }

    public function __toString() : string {
        return implode(', ', array_filter([$this->lastName, $this->firstName]));
    }

    public function getFormId() : string {
        return "({$this->id}) " . implode(', ', array_filter([$this->lastName, $this->firstName]));
    }

    public function setLastName(?string $lastName) : self {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLastName() : ?string {
        return $this->lastName;
    }

    public function setFirstName(?string $firstName) : self {
        $this->firstName = $firstName;

        return $this;
    }

    public function getFirstName() : ?string {
        return $this->firstName;
    }

    public function setTitle(?string $title) : self {
        $this->title = $title;

        return $this;
    }

    public function getTitle() : ?string {
        return $this->title;
    }

    public function setGender(?string $gender) : self {
        $this->gender = $gender;

        return $this;
    }

    public function getGender() : ?string {
        return $this->gender;
    }

    public function setDob(?string $dob) : self {
        $this->dob = $dob;

        return $this;
    }

    public function getDob() : ?string {
        if ('0000-00-00' === $this->dob) {
            return null;
        }

        return $this->dob;
    }

    public function setDod(?string $dod) : self {
        $this->dod = $dod;

        return $this;
    }

    public function getDod() : ?string {
        if ('0000-00-00' === $this->dod) {
            return null;
        }

        return $this->dod;
    }

    public function setNotes(?string $notes) : self {
        $this->notes = $notes;

        return $this;
    }

    public function getNotes() : ?string {
        return $this->notes;
    }

    public function setFinalcheck(bool $finalcheck) : self {
        $this->finalcheck = $finalcheck;

        return $this;
    }

    public function getFinalcheck() : bool {
        return $this->finalcheck;
    }

    public function setCityOfBirth(?Geonames $cityOfBirth = null) : self {
        $this->cityOfBirth = $cityOfBirth;

        return $this;
    }

    public function getCityOfBirth() : ?Geonames {
        return $this->cityOfBirth;
    }

    public function setCityOfDeath(?Geonames $cityOfDeath = null) : self {
        $this->cityOfDeath = $cityOfDeath;

        return $this;
    }

    public function getCityOfDeath() : ?Geonames {
        return $this->cityOfDeath;
    }

    public function addTitleRole(TitleRole $titleRole) : self {
        $this->titleRoles[] = $titleRole;

        return $this;
    }

    public function removeTitleRole(TitleRole $titleRole) : void {
        $this->titleRoles->removeElement($titleRole);
    }

    public function getTitleRoles(bool $sort = false) : Collection {
        if ( ! $sort) {
            return $this->titleRoles;
        }

        $iterator = $this->titleRoles->getIterator();
        $iterator->uasort(function (TitleRole $a, TitleRole $b) {
            $dateCmp = $a->getTitle()->getPubdate() <=> $b->getTitle()->getPubdate();
            if (0 !== $dateCmp) {
                return $dateCmp;
            }

            return strcasecmp($a->getTitle()->getTitle(), $b->getTitle()->getTitle());
        });

        return new ArrayCollection(iterator_to_array($iterator));
    }

    public function setViafUrl(?string $viafUrl = null) : self {
        $this->viafUrl = $viafUrl;

        return $this;
    }

    public function getViafUrl() : ?string {
        return $this->viafUrl;
    }

    public function setWikipediaUrl(?string $wikipediaUrl = null) : self {
        $this->wikipediaUrl = $wikipediaUrl;

        return $this;
    }

    public function getWikipediaUrl() : ?string {
        return $this->wikipediaUrl;
    }

    public function setImageUrl(?string $imageUrl = null) : self {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getImageUrl() : ?string {
        return $this->imageUrl;
    }

    /**
     * @return Collection<int,Firm>
     */
    public function getRelatedFirms() : Collection {
        return $this->relatedFirms;
    }

    public function addRelatedFirm(Firm $relatedFirm) : self {
        if ( ! $this->relatedFirms->contains($relatedFirm)) {
            $this->relatedFirms[] = $relatedFirm;
        }

        return $this;
    }

    public function removeRelatedFirm(Firm $relatedFirm) : self {
        $this->relatedFirms->removeElement($relatedFirm);

        return $this;
    }
}
