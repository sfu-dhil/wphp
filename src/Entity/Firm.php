<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

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
class Firm extends AbstractEntity {
    public const MALE = 'M';

    public const FEMALE = 'F';

    public const UNKNOWN = 'U';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1, nullable=false, options={"default": "U"})
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="street_address", type="text", nullable=true)
     */
    private $streetAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="start_date", type="string", length=4, nullable=true)
     */
    private $startDate;

    /**
     * @var string
     *
     * @ORM\Column(name="end_date", type="string", length=4, nullable=true)
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;

    /**
     * @var bool
     *
     * @ORM\Column(name="finalcheck", type="boolean", nullable=false)
     */
    private $finalcheck = false;

    /**
     * @var Geonames
     *
     * @ORM\ManyToOne(targetEntity="Geonames", inversedBy="firms")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="city_id", referencedColumnName="geonameid")
     * })
     */
    private $city;

    /**
     * @var Collection<int,TitleFirmrole>
     * @ORM\OneToMany(targetEntity="TitleFirmrole", mappedBy="firm")
     */
    private $titleFirmroles;

    /**
     * Firm sources are where the bibliographic information comes from.
     *
     * @var Collection<int,FirmSource>
     * @ORM\OneToMany(targetEntity="FirmSource", mappedBy="firm")
     */
    private $firmSources;

    /**
     * @var Collection<int,Person>
     * @ORM\ManyToMany(targetEntity="Person", mappedBy="relatedFirms")
     */
    private $relatedPeople;

    /**
     * @var Collection<int,Firm>
     * @ORM\ManyToMany(targetEntity="Firm", inversedBy="firmsRelated")
     */
    private $relatedFirms;

    /**
     * @var Collection<int,Firm>
     * @ORM\ManyToMany(targetEntity="Firm", mappedBy="relatedFirms")
     */
    private $firmsRelated;

    /**
     * Construct a new firm.
     */
    public function __construct() {
        parent::__construct();
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

    public function getFormId() : string {
        return "({$this->id}) {$this->name}";
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Firm
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set streetAddress.
     *
     * @param string $streetAddress
     *
     * @return Firm
     */
    public function setStreetAddress($streetAddress) {
        $this->streetAddress = $streetAddress;

        return $this;
    }

    /**
     * Get streetAddress.
     *
     * @return string
     */
    public function getStreetAddress() {
        return $this->streetAddress;
    }

    /**
     * Set startDate.
     *
     * @param string $startDate
     *
     * @return Firm
     */
    public function setStartDate($startDate) {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate.
     *
     * @return null|string
     */
    public function getStartDate() {
        if ('0000-00-00' === $this->startDate) {
            return null;
        }

        return $this->startDate;
    }

    /**
     * Set endDate.
     *
     * @param string $endDate
     *
     * @return Firm
     */
    public function setEndDate($endDate) {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate.
     *
     * @return null|string
     */
    public function getEndDate() {
        if ('0000-00-00' === $this->endDate) {
            return null;
        }

        return $this->endDate;
    }

    /**
     * Set notes.
     *
     * @param string $notes
     *
     * @return self
     */
    public function setNotes($notes) {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes.
     *
     * @return string
     */
    public function getNotes() {
        return $this->notes;
    }

    /**
     * Set finalcheck.
     *
     * @param bool $finalcheck
     *
     * @return self
     */
    public function setFinalcheck($finalcheck) {
        $this->finalcheck = $finalcheck;

        return $this;
    }

    /**
     * Get finalcheck.
     *
     * @return bool
     */
    public function getFinalcheck() {
        return $this->finalcheck;
    }

    /**
     * Set city.
     *
     * @param Geonames $city
     *
     * @return self
     */
    public function setCity(?Geonames $city = null) {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return null|Geonames
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Add titleFirmrole.
     *
     * @return self
     */
    public function addTitleFirmrole(TitleFirmrole $titleFirmrole) {
        $this->titleFirmroles[] = $titleFirmrole;

        return $this;
    }

    /**
     * Remove titleFirmrole.
     */
    public function removeTitleFirmrole(TitleFirmrole $titleFirmrole) : void {
        $this->titleFirmroles->removeElement($titleFirmrole);
    }

    /**
     * Get titleFirmroles for the firm, optionally sorted by name.
     *
     * @param bool $sort
     *
     * @return Collection<int,TitleFirmrole>
     */
    public function getTitleFirmroles($sort = false) {
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

    /**
     * Set gender.
     *
     * @param null|string $gender
     *
     * @return self
     */
    public function setGender($gender = null) {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender.
     *
     * @return null|string
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * Set the sources for this firm.
     *
     * @param Collection<int,FirmSource> $firmSources
     */
    public function setFirmSources($firmSources) : void {
        $this->firmSources = $firmSources;
    }

    /**
     * Add firmSource.
     *
     * @return self
     */
    public function addFirmSource(FirmSource $firmSource) {
        $this->firmSources[] = $firmSource;

        return $this;
    }

    /**
     * Remove firmSource.
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeFirmSource(FirmSource $firmSource) {
        return $this->firmSources->removeElement($firmSource);
    }

    /**
     * Get firmSources.
     *
     * @return Collection<int,FirmSource>
     */
    public function getFirmSources() {
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
