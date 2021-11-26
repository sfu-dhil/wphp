<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Geonames.
 *
 * @ORM\Table(name="geonames",
 *     indexes={
 *         @ORM\Index(name="geonames_search_idx", columns={"name", "geonameid", "country"}),
 *         @ORM\Index(name="geonames_names_ft", columns={"alternatenames", "name"}, flags={"fulltext"})
 *     })
 *     @ORM\Entity(repositoryClass="App\Repository\GeonamesRepository")
 */
class Geonames {
    /**
     * @ORM\Column(name="geonameid", type="integer", nullable=false)
     * @ORM\Id
     */
    private int $geonameid;

    /**
     * @ORM\Column(name="name", type="string", length=200, nullable=true)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(name="asciiname", type="string", length=200, nullable=true)
     */
    private ?string  $asciiname = null;

    /**
     * @ORM\Column(name="alternatenames", type="string", length=4000, nullable=true)
     */
    private ?string  $alternatenames = null;

    /**
     * @ORM\Column(name="latitude", type="decimal", precision=10, scale=7, nullable=true)
     */
    private ?float $latitude = null;

    /**
     * @ORM\Column(name="longitude", type="decimal", precision=10, scale=7, nullable=true)
     */
    private ?float $longitude = null;

    /**
     * @ORM\Column(name="fclass", type="string", length=1, nullable=true)
     */
    private ?string  $fclass = null;

    /**
     * @ORM\Column(name="fcode", type="string", length=10, nullable=true)
     */
    private ?string  $fcode = null;

    /**
     * @ORM\Column(name="country", type="string", length=2, nullable=true)
     */
    private ?string $country = null;

    /**
     * @ORM\Column(name="cc2", type="string", length=60, nullable=true)
     */
    private ?string $cc2 = null;

    /**
     * @ORM\Column(name="admin1", type="string", length=20, nullable=true)
     */
    private ?string  $admin1 = null;

    /**
     * @ORM\Column(name="admin2", type="string", length=80, nullable=true)
     */
    private ?string  $admin2 = null;

    /**
     * @ORM\Column(name="admin3", type="string", length=20, nullable=true)
     */
    private ?string  $admin3 = null;

    /**
     * @ORM\Column(name="admin4", type="string", length=20, nullable=true)
     */
    private ?string  $admin4 = null;

    /**
     * @ORM\Column(name="population", type="integer", nullable=true)
     */
    private ?int $population = null;

    /**
     * @ORM\Column(name="elevation", type="integer", nullable=true)
     */
    private ?int $elevation = null;

    /**
     * @ORM\Column(name="gtopo30", type="integer", nullable=true)
     */
    private ?int $gtopo30 = null;

    /**
     * @ORM\Column(name="timezone", type="string", length=40, nullable=true)
     */
    private ?string $timezone = null;

    /**
     * @var ?DateTimeImmutable
     *
     * @ORM\Column(name="moddate", type="date_immutable", nullable=true)
     */
    private ?DateTimeImmutable $moddate = null;

    /**
     * @var Collection|Title[]
     * @ORM\OneToMany(targetEntity="Title", mappedBy="locationOfPrinting")
     */
    private $titles;

    /**
     * @var Collection|Title[]
     * @ORM\OneToMany(targetEntity="Firm", mappedBy="city")
     */
    private $firms;

    /**
     * @var Collection|Person[]
     * @ORM\OneToMany(targetEntity="Person", mappedBy="cityOfBirth")
     */
    private $peopleBorn;

    /**
     * @var Collection|Title[]
     * @ORM\OneToMany(targetEntity="Person", mappedBy="cityOfDeath")
     */
    private $peopleDied;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->titles = new ArrayCollection();
        $this->firms = new ArrayCollection();
        $this->peopleBorn = new ArrayCollection();
        $this->peopleDied = new ArrayCollection();
    }

    /**
     * Return the name and country of this place.
     */
    public function __toString() : string {
        return $this->name . ' (' . $this->country . ')';
    }

    public function getGeonameid() : ?int {
        return $this->geonameid;
    }

    public function setGeonameid(?int $geonameid) : self {
        $this->geonameid = $geonameid;

        return $this;
    }

    public function getName() : ?string {
        return $this->name;
    }

    public function setName(?string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getAsciiname() : ?string {
        return $this->asciiname;
    }

    public function setAsciiname(?string $asciiname) : self {
        $this->asciiname = $asciiname;

        return $this;
    }

    public function getAlternatenames() : ?string {
        return $this->alternatenames;
    }

    public function setAlternatenames(?string $alternatenames) : self {
        $this->alternatenames = $alternatenames;

        return $this;
    }

    public function getLatitude() : ?float {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude) : self {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude() : ?float {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude) : self {
        $this->longitude = $longitude;

        return $this;
    }

    public function getFclass() : ?string {
        return $this->fclass;
    }

    public function setFclass(?string $fclass) : self {
        $this->fclass = $fclass;

        return $this;
    }

    public function getFcode() : ?string {
        return $this->fcode;
    }

    public function setFcode(?string $fcode) : self {
        $this->fcode = $fcode;

        return $this;
    }

    public function getCountry() : ?string {
        return $this->country;
    }

    public function setCountry(?string $country) : self {
        $this->country = $country;

        return $this;
    }

    public function getCc2() : ?string {
        return $this->cc2;
    }

    public function setCc2(?string $cc2) : self {
        $this->cc2 = $cc2;

        return $this;
    }

    public function getAdmin1() : ?string {
        return $this->admin1;
    }

    public function setAdmin1(?string $admin1) : self {
        $this->admin1 = $admin1;

        return $this;
    }

    public function getAdmin2() : ?string {
        return $this->admin2;
    }

    public function setAdmin2(?string $admin2) : self {
        $this->admin2 = $admin2;

        return $this;
    }

    public function getAdmin3() : ?string {
        return $this->admin3;
    }

    public function setAdmin3(?string $admin3) : self {
        $this->admin3 = $admin3;

        return $this;
    }

    public function getAdmin4() : ?string {
        return $this->admin4;
    }

    public function setAdmin4(?string $admin4) : self {
        $this->admin4 = $admin4;

        return $this;
    }

    public function getPopulation() : ?int {
        return $this->population;
    }

    public function setPopulation(?int $population) : self {
        $this->population = $population;

        return $this;
    }

    public function getElevation() : ?int {
        return $this->elevation;
    }

    public function setElevation(?int $elevation) : self {
        $this->elevation = $elevation;

        return $this;
    }

    public function getGtopo30() : ?int {
        return $this->gtopo30;
    }

    public function setGtopo30(?int $gtopo30) : self {
        $this->gtopo30 = $gtopo30;

        return $this;
    }

    public function getTimezone() : ?string {
        return $this->timezone;
    }

    public function setTimezone(?string $timezone) : self {
        $this->timezone = $timezone;

        return $this;
    }

    public function getModdate() : ?DateTimeImmutable {
        return $this->moddate;
    }

    public function setModdate(?DateTimeImmutable $moddate) : self {
        $this->moddate = $moddate;

        return $this;
    }

    /**
     * @return Collection|Title[]
     */
    public function getTitles() : Collection {
        return $this->titles;
    }

    public function addTitle(Title $title) : self {
        if ( ! $this->titles->contains($title)) {
            $this->titles[] = $title;
            $title->setLocationOfPrinting($this);
        }

        return $this;
    }

    public function removeTitle(Title $title) : self {
        if ($this->titles->removeElement($title)) {
            // set the owning side to null (unless already changed)
            if ($title->getLocationOfPrinting() === $this) {
                $title->setLocationOfPrinting(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Firm[]
     */
    public function getFirms() : Collection {
        return $this->firms;
    }

    public function addFirm(Firm $firm) : self {
        if ( ! $this->firms->contains($firm)) {
            $this->firms[] = $firm;
            $firm->setCity($this);
        }

        return $this;
    }

    public function removeFirm(Firm $firm) : self {
        if ($this->firms->removeElement($firm)) {
            // set the owning side to null (unless already changed)
            if ($firm->getCity() === $this) {
                $firm->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getPeopleBorn() : Collection {
        return $this->peopleBorn;
    }

    public function addPeopleBorn(Person $peopleBorn) : self {
        if ( ! $this->peopleBorn->contains($peopleBorn)) {
            $this->peopleBorn[] = $peopleBorn;
            $peopleBorn->setCityOfBirth($this);
        }

        return $this;
    }

    public function removePeopleBorn(Person $peopleBorn) : self {
        if ($this->peopleBorn->removeElement($peopleBorn)) {
            // set the owning side to null (unless already changed)
            if ($peopleBorn->getCityOfBirth() === $this) {
                $peopleBorn->setCityOfBirth(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getPeopleDied() : Collection {
        return $this->peopleDied;
    }

    public function addPeopleDied(Person $peopleDied) : self {
        if ( ! $this->peopleDied->contains($peopleDied)) {
            $this->peopleDied[] = $peopleDied;
            $peopleDied->setCityOfDeath($this);
        }

        return $this;
    }

    public function removePeopleDied(Person $peopleDied) : self {
        if ($this->peopleDied->removeElement($peopleDied)) {
            // set the owning side to null (unless already changed)
            if ($peopleDied->getCityOfDeath() === $this) {
                $peopleDied->setCityOfDeath(null);
            }
        }

        return $this;
    }
}
