<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Firm.
 *
 * @ORM\Table(name="firm",
 *     indexes={
 *         @ORM\Index(name="firm_name_ft", columns={"name"}, flags={"fulltext"}),
 *         @ORM\Index(name="firm_address_ft", columns={"street_address"}, flags={"fulltext"}),
 *     },
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="firm_uniq", columns={"name", "city_id", "start_date", "end_date"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\FirmRepository")
 */
class Firm {
    public const MALE = 'M';

    public const FEMALE = 'F';

    public const UNKNOWN = 'U';

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(name="gender", type="string", length=1, nullable=false, options={"default" = "U"})
     */
    private string $gender;

    /**
     * @ORM\Column(name="street_address", type="text", nullable=true)
     */
    private ?string $streetAddress = null;

    /**
     * @ORM\Column(name="start_date", type="string", length=4, nullable=true)
     */
    private ?string $startDate = null;

    /**
     * @ORM\Column(name="end_date", type="string", length=4, nullable=true)
     */
    private ?string  $endDate = null;

    /**
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private ?string $notes = null;

    /**
     * @ORM\Column(name="finalcheck", type="boolean", nullable=false)
     */
    private bool $finalcheck = false;

    /**
     * @ORM\ManyToOne(targetEntity="Geonames", inversedBy="firms")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="city_id", referencedColumnName="geonameid")
     * })
     */
    private ?Geonames $city = null;

    /**
     * @var Collection|TitleFirmrole[]
     * @ORM\OneToMany(targetEntity="TitleFirmrole", mappedBy="firm")
     */
    private $titleFirmroles;

    /**
     * Firm sources are where the bibliographic information comes from.
     *
     * @var Collection|FirmSource[]
     * @ORM\OneToMany(targetEntity="FirmSource", mappedBy="firm")
     */
    private $firmSources;

    /**
     * @var Collection|Person[]
     * @ORM\ManyToMany(targetEntity="Person", mappedBy="relatedFirms")
     */
    private $relatedPeople;

    /**
     * @var Collection|Firm[]
     * @ORM\ManyToMany(targetEntity="Firm", inversedBy="firmsRelated")
     */
    private $relatedFirms;

    /**
     * @var Collection|Firm[]
     * @ORM\ManyToMany(targetEntity="Firm", mappedBy="relatedFirms")
     */
    private $firmsRelated;

    /**
     * Construct a new firm.
     */
    public function __construct() {
        $this->gender = self::UNKNOWN;
        $this->titleFirmroles = new ArrayCollection();
        $this->relatedPeople = new ArrayCollection();
        $this->relatedFirms = new ArrayCollection();
        $this->firmsRelated = new ArrayCollection();
        $this->firmSources = new ArrayCollection();
    }

    /**
     * Get the name of the firm.
     */
    public function __toString() : string {
        return $this->name;
    }

    public function getFormId() {
        return "({$this->id}) {$this->name}";
    }

    /**
     * Get titleFirmroles for the firm, optionally sorted by name.
     */
    public function getTitleFirmroles(bool $sort = false) : Collection {
        if ( ! $sort) {
            return $this->titleFirmroles;
        }

        $iterator = $this->titleFirmroles->getIterator();
        $iterator->uasort(function(TitleFirmrole $a, TitleFirmrole $b) {
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

    public function getId() : ?int {
        return $this->id;
    }

    public function getName() : ?string {
        return $this->name;
    }

    public function setName(?string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getGender() : ?string {
        return $this->gender;
    }

    public function setGender(string $gender) : self {
        $this->gender = $gender;

        return $this;
    }

    public function getStreetAddress() : ?string {
        return $this->streetAddress;
    }

    public function setStreetAddress(?string $streetAddress) : self {
        $this->streetAddress = $streetAddress;

        return $this;
    }

    public function getStartDate() : ?string {
        if ('0000-00-00' === $this->startDate) {
            return null;
        }

        return $this->startDate;
    }

    public function setStartDate(?string $startDate) : self {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate() : ?string {
        if ('0000-00-00' === $this->endDate) {
            return null;
        }

        return $this->endDate;
    }

    public function setEndDate(?string $endDate) : self {
        $this->endDate = $endDate;

        return $this;
    }

    public function getNotes() : ?string {
        return $this->notes;
    }

    public function setNotes(?string $notes) : self {
        $this->notes = $notes;

        return $this;
    }

    public function getFinalcheck() : ?bool {
        return $this->finalcheck;
    }

    public function setFinalcheck(bool $finalcheck) : self {
        $this->finalcheck = $finalcheck;

        return $this;
    }

    public function getCity() : ?Geonames {
        return $this->city;
    }

    public function setCity(?Geonames $city) : self {
        $this->city = $city;

        return $this;
    }

    public function addTitleFirmrole(TitleFirmrole $titleFirmrole) : self {
        if ( ! $this->titleFirmroles->contains($titleFirmrole)) {
            $this->titleFirmroles[] = $titleFirmrole;
            $titleFirmrole->setFirm($this);
        }

        return $this;
    }

    public function removeTitleFirmrole(TitleFirmrole $titleFirmrole) : self {
        if ($this->titleFirmroles->removeElement($titleFirmrole)) {
            // set the owning side to null (unless already changed)
            if ($titleFirmrole->getFirm() === $this) {
                $titleFirmrole->setFirm(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FirmSource[]
     */
    public function getFirmSources() : Collection {
        return $this->firmSources;
    }

    public function addFirmSource(FirmSource $firmSource) : self {
        if ( ! $this->firmSources->contains($firmSource)) {
            $this->firmSources[] = $firmSource;
            $firmSource->setFirm($this);
        }

        return $this;
    }

    public function removeFirmSource(FirmSource $firmSource) : self {
        if ($this->firmSources->removeElement($firmSource)) {
            // set the owning side to null (unless already changed)
            if ($firmSource->getFirm() === $this) {
                $firmSource->setFirm(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getRelatedPeople() : Collection {
        return $this->relatedPeople;
    }

    public function addRelatedPerson(Person $relatedPerson) : self {
        if ( ! $this->relatedPeople->contains($relatedPerson)) {
            $this->relatedPeople[] = $relatedPerson;
            $relatedPerson->addRelatedFirm($this);
        }

        return $this;
    }

    public function removeRelatedPerson(Person $relatedPerson) : self {
        if ($this->relatedPeople->removeElement($relatedPerson)) {
            $relatedPerson->removeRelatedFirm($this);
        }

        return $this;
    }

    /**
     * @return Collection|Firm[]
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
     * @return Collection|Firm[]
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
