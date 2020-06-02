<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Geonames.
 *
 * @ORM\Table(name="geonames",
 *  indexes={
 *      @ORM\Index(name="geonames_search_idx", columns={"name", "geonameid", "country"}),
 *      @ORM\Index(name="geonames_names_ft", columns={"alternatenames", "name"}, flags={"fulltext"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\GeonamesRepository")
 */
class Geonames {
    /**
     * @var int
     *
     * @ORM\Column(name="geonameid", type="integer", nullable=false)
     * @ORM\Id
     */
    private $geonameid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=200, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="asciiname", type="string", length=200, nullable=true)
     */
    private $asciiname;

    /**
     * @var string
     *
     * @ORM\Column(name="alternatenames", type="string", length=4000, nullable=true)
     */
    private $alternatenames;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="decimal", precision=10, scale=7, nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="decimal", precision=10, scale=7, nullable=true)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="fclass", type="string", length=1, nullable=true)
     */
    private $fclass;

    /**
     * @var string
     *
     * @ORM\Column(name="fcode", type="string", length=10, nullable=true)
     */
    private $fcode;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=2, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="cc2", type="string", length=60, nullable=true)
     */
    private $cc2;

    /**
     * @var string
     *
     * @ORM\Column(name="admin1", type="string", length=20, nullable=true)
     */
    private $admin1;

    /**
     * @var string
     *
     * @ORM\Column(name="admin2", type="string", length=80, nullable=true)
     */
    private $admin2;

    /**
     * @var string
     *
     * @ORM\Column(name="admin3", type="string", length=20, nullable=true)
     */
    private $admin3;

    /**
     * @var string
     *
     * @ORM\Column(name="admin4", type="string", length=20, nullable=true)
     */
    private $admin4;

    /**
     * @var int
     *
     * @ORM\Column(name="population", type="integer", nullable=true)
     */
    private $population;

    /**
     * @var int
     *
     * @ORM\Column(name="elevation", type="integer", nullable=true)
     */
    private $elevation;

    /**
     * @var int
     *
     * @ORM\Column(name="gtopo30", type="integer", nullable=true)
     */
    private $gtopo30;

    /**
     * @var string
     *
     * @ORM\Column(name="timezone", type="string", length=40, nullable=true)
     */
    private $timezone;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="moddate", type="date", nullable=true)
     */
    private $moddate;

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

    /**
     * Set geonameid.
     *
     * @param string $geonameid
     *
     * @return Geonames
     */
    public function setGeonameid($geonameid) {
        $this->geonameid = $geonameid;

        return $this;
    }

    /**
     * Get geonameid.
     *
     * @return int
     */
    public function getGeonameid() {
        return $this->geonameid;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Geonames
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
     * Set asciiname.
     *
     * @param string $asciiname
     *
     * @return Geonames
     */
    public function setAsciiname($asciiname) {
        $this->asciiname = $asciiname;

        return $this;
    }

    /**
     * Get asciiname.
     *
     * @return string
     */
    public function getAsciiname() {
        return $this->asciiname;
    }

    /**
     * Set alternatenames.
     *
     * @param string $alternatenames
     *
     * @return Geonames
     */
    public function setAlternatenames($alternatenames) {
        $this->alternatenames = $alternatenames;

        return $this;
    }

    /**
     * Get alternatenames.
     *
     * @return string
     */
    public function getAlternatenames() {
        return $this->alternatenames;
    }

    /**
     * Set latitude.
     *
     * @param string $latitude
     *
     * @return Geonames
     */
    public function setLatitude($latitude) {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude.
     *
     * @return string
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * Set longitude.
     *
     * @param string $longitude
     *
     * @return Geonames
     */
    public function setLongitude($longitude) {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude.
     *
     * @return string
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * Set fclass.
     *
     * @param string $fclass
     *
     * @return Geonames
     */
    public function setFclass($fclass) {
        $this->fclass = $fclass;

        return $this;
    }

    /**
     * Get fclass.
     *
     * @return string
     */
    public function getFclass() {
        return $this->fclass;
    }

    /**
     * Set fcode.
     *
     * @param string $fcode
     *
     * @return Geonames
     */
    public function setFcode($fcode) {
        $this->fcode = $fcode;

        return $this;
    }

    /**
     * Get fcode.
     *
     * @return string
     */
    public function getFcode() {
        return $this->fcode;
    }

    /**
     * Set country.
     *
     * @param string $country
     *
     * @return Geonames
     */
    public function setCountry($country) {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.
     *
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Set cc2.
     *
     * @param string $cc2
     *
     * @return Geonames
     */
    public function setCc2($cc2) {
        $this->cc2 = $cc2;

        return $this;
    }

    /**
     * Get cc2.
     *
     * @return string
     */
    public function getCc2() {
        return $this->cc2;
    }

    /**
     * Set admin1.
     *
     * @param string $admin1
     *
     * @return Geonames
     */
    public function setAdmin1($admin1) {
        $this->admin1 = $admin1;

        return $this;
    }

    /**
     * Get admin1.
     *
     * @return string
     */
    public function getAdmin1() {
        return $this->admin1;
    }

    /**
     * Set admin2.
     *
     * @param string $admin2
     *
     * @return Geonames
     */
    public function setAdmin2($admin2) {
        $this->admin2 = $admin2;

        return $this;
    }

    /**
     * Get admin2.
     *
     * @return string
     */
    public function getAdmin2() {
        return $this->admin2;
    }

    /**
     * Set admin3.
     *
     * @param string $admin3
     *
     * @return Geonames
     */
    public function setAdmin3($admin3) {
        $this->admin3 = $admin3;

        return $this;
    }

    /**
     * Get admin3.
     *
     * @return string
     */
    public function getAdmin3() {
        return $this->admin3;
    }

    /**
     * Set admin4.
     *
     * @param string $admin4
     *
     * @return Geonames
     */
    public function setAdmin4($admin4) {
        $this->admin4 = $admin4;

        return $this;
    }

    /**
     * Get admin4.
     *
     * @return string
     */
    public function getAdmin4() {
        return $this->admin4;
    }

    /**
     * Set population.
     *
     * @param int $population
     *
     * @return Geonames
     */
    public function setPopulation($population) {
        $this->population = $population;

        return $this;
    }

    /**
     * Get population.
     *
     * @return int
     */
    public function getPopulation() {
        return $this->population;
    }

    /**
     * Set elevation.
     *
     * @param int $elevation
     *
     * @return Geonames
     */
    public function setElevation($elevation) {
        $this->elevation = $elevation;

        return $this;
    }

    /**
     * Get elevation.
     *
     * @return int
     */
    public function getElevation() {
        return $this->elevation;
    }

    /**
     * Set gtopo30.
     *
     * @param int $gtopo30
     *
     * @return Geonames
     */
    public function setGtopo30($gtopo30) {
        $this->gtopo30 = $gtopo30;

        return $this;
    }

    /**
     * Get gtopo30.
     *
     * @return int
     */
    public function getGtopo30() {
        return $this->gtopo30;
    }

    /**
     * Set timezone.
     *
     * @param string $timezone
     *
     * @return Geonames
     */
    public function setTimezone($timezone) {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * Get timezone.
     *
     * @return string
     */
    public function getTimezone() {
        return $this->timezone;
    }

    /**
     * Set moddate.
     *
     * @param DateTime $moddate
     *
     * @return Geonames
     */
    public function setModdate($moddate) {
        $this->moddate = $moddate;

        return $this;
    }

    /**
     * Get moddate.
     *
     * @return DateTime
     */
    public function getModdate() {
        return $this->moddate;
    }

    /**
     * Add title.
     *
     * @return Geonames
     */
    public function addTitle(Title $title) {
        $this->titles[] = $title;

        return $this;
    }

    /**
     * Remove title.
     */
    public function removeTitle(Title $title) : void {
        $this->titles->removeElement($title);
    }

    /**
     * Get titles.
     *
     * @return Collection
     */
    public function getTitles() {
        return $this->titles;
    }

    /**
     * Add firm.
     *
     * @return Geonames
     */
    public function addFirm(Firm $firm) {
        $this->firms[] = $firm;

        return $this;
    }

    /**
     * Remove firm.
     */
    public function removeFirm(Firm $firm) : void {
        $this->firms->removeElement($firm);
    }

    /**
     * Get firms.
     *
     * @return Collection
     */
    public function getFirms() {
        return $this->firms;
    }

    /**
     * Add peopleBorn.
     *
     * @return Geonames
     */
    public function addPeopleBorn(Person $peopleBorn) {
        $this->peopleBorn[] = $peopleBorn;

        return $this;
    }

    /**
     * Remove peopleBorn.
     */
    public function removePeopleBorn(Person $peopleBorn) : void {
        $this->peopleBorn->removeElement($peopleBorn);
    }

    /**
     * Get peopleBorn.
     *
     * @return Collection
     */
    public function getPeopleBorn() {
        return $this->peopleBorn;
    }

    /**
     * Add peopleDied.
     *
     * @return Geonames
     */
    public function addPeopleDied(Firm $peopleDied) {
        $this->peopleDied[] = $peopleDied;

        return $this;
    }

    /**
     * Remove peopleDied.
     */
    public function removePeopleDied(Firm $peopleDied) : void {
        $this->peopleDied->removeElement($peopleDied);
    }

    /**
     * Get peopleDied.
     *
     * @return Collection
     */
    public function getPeopleDied() {
        return $this->peopleDied;
    }
}
