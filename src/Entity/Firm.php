<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\FirmRepository;
use DateTimeInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

#[ORM\Table(name: 'firm')]
#[ORM\Index(name: 'firm_name_ft', columns: ['name'], flags: ['fulltext'])]
#[ORM\Index(name: 'firm_address_ft', columns: ['street_address'], flags: ['fulltext'])]
#[ORM\Entity(repositoryClass: FirmRepository::class)]
class Firm extends AbstractEntity {
    final public const MALE = 'M';

    final public const FEMALE = 'F';

    final public const UNKNOWN = 'U';

    #[ORM\Column(name: 'name', type: 'string', length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(name: 'gender', type: 'string', length: 1, nullable: false, options: ['default' => 'U'])]
    private ?string $gender;

    #[ORM\Column(name: 'street_address', type: 'text', nullable: true)]
    private ?string $streetAddress = null;

    #[ORM\Column(name: 'start_date', type: 'string', length: 4, nullable: true)]
    private ?string $startDate = null;

    #[ORM\Column(name: 'end_date', type: 'string', length: 4, nullable: true)]
    private ?string $endDate = null;

    #[ORM\Column(name: 'notes', type: 'text', nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(name: 'finalcheck', type: 'boolean', nullable: false)]
    private bool $finalcheck = false;

    #[ORM\JoinColumn(name: 'city_id', referencedColumnName: 'geonameid')]
    #[ORM\ManyToOne(targetEntity: Geonames::class, inversedBy: 'firms')]
    private ?Geonames $city = null;

    /**
     * @var Collection<int,TitleFirmrole>
     */
    #[ORM\OneToMany(targetEntity: TitleFirmrole::class, mappedBy: 'firm')]
    private array|Collection $titleFirmroles;

    /**
     * Firm sources are where the bibliographic information comes from.
     *
     * @var Collection<int,FirmSource>
     */
    #[ORM\OneToMany(targetEntity: FirmSource::class, mappedBy: 'firm')]
    private array|Collection $firmSources;

    /**
     * @var Collection<int,Person>
     */
    #[ORM\ManyToMany(targetEntity: Person::class, mappedBy: 'relatedFirms')]
    private array|Collection $relatedPeople;

    /**
     * @var Collection<int,Firm>
     */
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'firmsRelated')]
    private array|Collection $relatedFirms;

    /**
     * @var Collection<int,Firm>
     */
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'relatedFirms')]
    private array|Collection $firmsRelated;

    public function __construct() {
        parent::__construct();
        $this->gender = self::UNKNOWN;
        $this->titleFirmroles = new ArrayCollection();
        $this->relatedPeople = new ArrayCollection();
        $this->relatedFirms = new ArrayCollection();
        $this->firmsRelated = new ArrayCollection();
        $this->firmSources = new ArrayCollection();
    }

    public function __toString() : string {
        return $this->name;
    }

    public function getFormId() : string {
        return "({$this->id}) {$this->name}";
    }

    public function setName(?string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getName() : ?string {
        return $this->name;
    }

    public function setStreetAddress(?string $streetAddress) : self {
        $this->streetAddress = $streetAddress;

        return $this;
    }

    public function getStreetAddress() : ?string {
        return $this->streetAddress;
    }

    public function setStartDate(?string $startDate) : self {
        $this->startDate = $startDate;

        return $this;
    }

    public function getStartDate() : ?string {
        if ('0000-00-00' === $this->startDate) {
            return null;
        }

        return $this->startDate;
    }

    public function setEndDate(?string $endDate) : self {
        $this->endDate = $endDate;

        return $this;
    }

    public function getEndDate() : ?string {
        if ('0000-00-00' === $this->endDate) {
            return null;
        }

        return $this->endDate;
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

    public function setCity(?Geonames $city = null) : self {
        $this->city = $city;

        return $this;
    }

    public function getCity() : ?Geonames {
        return $this->city;
    }

    public function addTitleFirmrole(TitleFirmrole $titleFirmrole) : self {
        $this->titleFirmroles[] = $titleFirmrole;

        return $this;
    }

    public function removeTitleFirmrole(TitleFirmrole $titleFirmrole) : void {
        $this->titleFirmroles->removeElement($titleFirmrole);
    }

    /**
     * @return Collection<int,TitleFirmrole>
     */
    public function getTitleFirmroles(bool $sort = false) : Collection {
        if ( ! $sort) {
            return $this->titleFirmroles;
        }

        $iterator = $this->titleFirmroles->getIterator();
        $iterator->uasort(function (TitleFirmrole $a, TitleFirmrole $b) {
            if ($a->getTitle()->getPubdate() === $b->getTitle()->getPubDate()) {
                if ($a->getFirmrole()->getName() === $b->getFirmrole()->getName()) {
                    return strcasecmp($a->getTitle()->getTitle(), $b->getTitle()->getTitle());
                }

                return strcasecmp($a->getFirmrole()->getName(), $b->getFirmrole()->getName());
            }

            return $a->getTitle()->getPubdate() <=> $b->getTitle()->getPubDate();
        });

        return new ArrayCollection(iterator_to_array($iterator));
    }

    public function setGender(?string $gender = null) : self {
        $this->gender = $gender;

        return $this;
    }

    public function getGender() : ?string {
        return $this->gender;
    }

    public function getGenderString() : ?string {
        switch ($this->gender){
            case Firm::MALE:
                return 'Male';
            case Firm::FEMALE:
                return 'Female';
            case Firm::UNKNOWN:
                return 'Unknown';
            default:
                return null;
        }
    }

    /**
     * @param Collection<int,FirmSource> $firmSources
     */
    public function setFirmSources(Collection $firmSources) : void {
        $this->firmSources = $firmSources;
    }

    public function addFirmSource(FirmSource $firmSource) : self {
        $this->firmSources[] = $firmSource;

        return $this;
    }

    public function removeFirmSource(FirmSource $firmSource) : bool {
        return $this->firmSources->removeElement($firmSource);
    }

    /**
     * @return Collection<int,FirmSource>
     */
    public function getFirmSources() : Collection {
        return $this->firmSources;
    }

    /**
     * @return Collection<int,Person>
     */
    public function getRelatedPeople() : Collection {
        return $this->relatedPeople;
    }

    public function addRelatedPerson(Person $relatedPerson) : self {
        if ( ! $this->relatedPeople->contains($relatedPerson)) {
            $this->relatedPeople[] = $relatedPerson;
        }

        return $this;
    }

    public function removeRelatedPerson(Person $relatedPerson) : self {
        $this->relatedPeople->removeElement($relatedPerson);

        return $this;
    }

    /**
     * @return Collection<int,Firm>
     */
    public function getRelatedFirms() : Collection {
        return $this->relatedFirms;
    }

    public function addRelatedFirm(self $relatedFirm) : self {
        if ( ! $this->relatedFirms->contains($relatedFirm)) {
            $this->relatedFirms[] = $relatedFirm;
        }

        return $this;
    }

    public function removeRelatedFirm(self $relatedFirm) : self {
        $this->relatedFirms->removeElement($relatedFirm);

        return $this;
    }

    /**
     * @return Collection<int,Firm>
     */
    public function getFirmsRelated() : Collection {
        return $this->firmsRelated;
    }

    public function addFirmsRelated(self $firmsRelated) : self {
        if ( ! $this->firmsRelated->contains($firmsRelated)) {
            $this->firmsRelated[] = $firmsRelated;
            $firmsRelated->addRelatedFirm($this);
        }

        return $this;
    }

    public function removeFirmsRelated(self $firmsRelated) : self {
        if ($this->firmsRelated->removeElement($firmsRelated)) {
            $firmsRelated->removeRelatedFirm($this);
        }

        return $this;
    }
}
