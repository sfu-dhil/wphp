<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GeonamesRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Table(name: 'geonames')]
#[ORM\Index(name: 'geonames_search_idx', columns: ['name', 'geonameid', 'country'])]
#[ORM\Index(name: 'geonames_names_ft', columns: ['alternatenames', 'name'], flags: ['fulltext'])]
#[ORM\Entity(repositoryClass: GeonamesRepository::class)]
class Geonames implements Stringable {
    #[ORM\Column(name: 'geonameid', type: 'integer', nullable: false)]
    #[ORM\Id]
    private ?int $geonameid = null;

    #[ORM\Column(name: 'name', type: 'string', length: 200, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(name: 'asciiname', type: 'string', length: 200, nullable: true)]
    private ?string $asciiname;

    #[ORM\Column(name: 'alternatenames', type: 'string', length: 4000, nullable: true)]
    private ?string $alternatenames;

    #[ORM\Column(name: 'latitude', type: 'decimal', precision: 10, scale: 7, nullable: true)]
    private ?string $latitude;

    #[ORM\Column(name: 'longitude', type: 'decimal', precision: 10, scale: 7, nullable: true)]
    private ?string $longitude;

    #[ORM\Column(name: 'fclass', type: 'string', length: 1, nullable: true)]
    private ?string $fclass;

    #[ORM\Column(name: 'fcode', type: 'string', length: 10, nullable: true)]
    private ?string $fcode;

    #[ORM\Column(name: 'country', type: 'string', length: 2, nullable: true)]
    private ?string $country;

    #[ORM\Column(name: 'cc2', type: 'string', length: 60, nullable: true)]
    private ?string $cc2;

    #[ORM\Column(name: 'admin1', type: 'string', length: 20, nullable: true)]
    private ?string $admin1;

    #[ORM\Column(name: 'admin2', type: 'string', length: 80, nullable: true)]
    private ?string $admin2;

    #[ORM\Column(name: 'admin3', type: 'string', length: 20, nullable: true)]
    private ?string $admin3;

    #[ORM\Column(name: 'admin4', type: 'string', length: 20, nullable: true)]
    private ?string $admin4;

    #[ORM\Column(name: 'population', type: 'integer', nullable: true)]
    private ?int $population;

    #[ORM\Column(name: 'elevation', type: 'integer', nullable: true)]
    private ?int $elevation;

    #[ORM\Column(name: 'gtopo30', type: 'integer', nullable: true)]
    private ?int $gtopo30;

    #[ORM\Column(name: 'timezone', type: 'string', length: 40, nullable: true)]
    private ?string $timezone;

    #[ORM\Column(name: 'moddate', type: 'date', nullable: true)]
    private DateTimeInterface $moddate;

    /**
     * @var Collection<int,Title>
     */
    #[ORM\OneToMany(targetEntity: Title::class, mappedBy: 'locationOfPrinting')]
    private Collection|array $titles;

    /**
     * @var Collection<int,Firm>
     */
    #[ORM\OneToMany(targetEntity: Firm::class, mappedBy: 'city')]
    private Collection|array $firms;

    /**
     * @var Collection<int,Person>
     */
    #[ORM\OneToMany(targetEntity: Person::class, mappedBy: 'cityOfBirth')]
    private Collection|array $peopleBorn;

    /**
     * @var Collection<int,Person>
     */
    #[ORM\OneToMany(targetEntity: Person::class, mappedBy: 'cityOfDeath')]
    private Collection|array $peopleDied;

    public function __construct() {
        $this->titles = new ArrayCollection();
        $this->firms = new ArrayCollection();
        $this->peopleBorn = new ArrayCollection();
        $this->peopleDied = new ArrayCollection();
    }

    public function __toString() : string {
        return $this->name . ' (' . $this->country . ')';
    }

    public function setGeonameid(?int $geonameid) : self {
        $this->geonameid = $geonameid;

        return $this;
    }

    public function getGeonameid() : ?int {
        return $this->geonameid;
    }

    public function setName(?string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getName() : ?string {
        return $this->name;
    }

    public function setAsciiname(?string $asciiname) : self {
        $this->asciiname = $asciiname;

        return $this;
    }

    public function getAsciiname() : ?string {
        return $this->asciiname;
    }

    public function setAlternatenames(?string $alternatenames) : self {
        $this->alternatenames = $alternatenames;

        return $this;
    }

    public function getAlternatenames() : ?string {
        return $this->alternatenames;
    }

    public function setLatitude(?string $latitude) : self {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLatitude() : ?string {
        return $this->latitude;
    }

    public function setLongitude(?string $longitude) : self {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLongitude() : ?string {
        return $this->longitude;
    }

    public function setFclass(?string $fclass) : self {
        $this->fclass = $fclass;

        return $this;
    }

    public function getFclass() : ?string {
        return $this->fclass;
    }

    public function setFcode(?string $fcode) : self {
        $this->fcode = $fcode;

        return $this;
    }

    public function getFcode() : ?string {
        return $this->fcode;
    }

    public function setCountry(?string $country) : self {
        $this->country = $country;

        return $this;
    }

    public function getCountry() : ?string {
        return $this->country;
    }

    public function setCc2(?string $cc2) : self {
        $this->cc2 = $cc2;

        return $this;
    }

    public function getCc2() : ?string {
        return $this->cc2;
    }

    public function setAdmin1(?string $admin1) : self {
        $this->admin1 = $admin1;

        return $this;
    }

    public function getAdmin1() : ?string {
        return $this->admin1;
    }

    public function setAdmin2(?string $admin2) : self {
        $this->admin2 = $admin2;

        return $this;
    }

    public function getAdmin2() : ?string {
        return $this->admin2;
    }

    public function setAdmin3(?string $admin3) : self {
        $this->admin3 = $admin3;

        return $this;
    }

    public function getAdmin3() : ?string {
        return $this->admin3;
    }

    public function setAdmin4(?string $admin4) : self {
        $this->admin4 = $admin4;

        return $this;
    }

    public function getAdmin4() : ?string {
        return $this->admin4;
    }

    public function setPopulation(?int $population) : self {
        $this->population = $population;

        return $this;
    }

    public function getPopulation() : ?int {
        return $this->population;
    }

    public function setElevation(?int $elevation) : self {
        $this->elevation = $elevation;

        return $this;
    }

    public function getElevation() : ?int {
        return $this->elevation;
    }

    public function setGtopo30(?int $gtopo30) : self {
        $this->gtopo30 = $gtopo30;

        return $this;
    }

    public function getGtopo30() : ?int {
        return $this->gtopo30;
    }

    public function setTimezone(?string $timezone) : self {
        $this->timezone = $timezone;

        return $this;
    }

    public function getTimezone() : ?string {
        return $this->timezone;
    }

    public function setModdate(?DateTimeInterface $moddate) : self {
        $this->moddate = $moddate;

        return $this;
    }

    public function getModdate() : ?DateTimeInterface {
        return $this->moddate;
    }

    public function addTitle(Title $title) : self {
        $this->titles[] = $title;

        return $this;
    }

    public function removeTitle(Title $title) : void {
        $this->titles->removeElement($title);
    }

    /**
     * @return Collection<int,Title>
     */
    public function getTitles() : Collection {
        return $this->titles;
    }

    public function addFirm(Firm $firm) : self {
        $this->firms[] = $firm;

        return $this;
    }

    public function removeFirm(Firm $firm) : void {
        $this->firms->removeElement($firm);
    }

    /**
     * @return Collection<int,Firm>
     */
    public function getFirms() : Collection {
        return $this->firms;
    }

    public function addPeopleBorn(Person $peopleBorn) : self {
        $this->peopleBorn[] = $peopleBorn;

        return $this;
    }

    public function removePeopleBorn(Person $peopleBorn) : void {
        $this->peopleBorn->removeElement($peopleBorn);
    }

    /**
     * @return Collection<int,Person>
     */
    public function getPeopleBorn() : Collection {
        return $this->peopleBorn;
    }

    public function addPeopleDied(Person $peopleDied) : self {
        $this->peopleDied->add($peopleDied);

        return $this;
    }

    public function removePeopleDied(Person $peopleDied) : void {
        $this->peopleDied->removeElement($peopleDied);
    }

    /**
     * @return Collection<int,Person>
     */
    public function getPeopleDied() : Collection {
        return $this->peopleDied;
    }
}
