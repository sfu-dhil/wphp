<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Firm
 *
 * @ORM\Table(name="firm",
 *  indexes={
 *      @ORM\Index(name="firm_name_ft", columns={"name"}, flags={"fulltext"}),
 *      @ORM\Index(name="firm_address_ft", columns={"street_address"}, flags={"fulltext"}),
 *  },
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="firm_uniq", columns={"name", "city_id", "start_date", "end_date"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FirmRepository")
 */
class Firm
{

    const MALE = 'M';

    const FEMALE = 'F';

    const UNKNOWN = 'U';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1, nullable=false, options={"default"="U"})
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
     * @var boolean
     *
     * @ORM\Column(name="finalcheck", type="boolean", nullable=false)
     */
    private $finalcheck = '0';

    /**
     * @var Geonames
     *
     * @ORM\ManyToOne(targetEntity="Geonames", inversedBy="firms")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city_id", referencedColumnName="geonameid")
     * })
     */
    private $city;

    /**
     * @var Collection|TitleFirmrole[]
     * @ORM\OneToMany(targetEntity="TitleFirmrole", mappedBy="firm")
     */
    private $titleFirmroles;

    /**
     * Construct a new firm.
     */
    public function __construct() {
        $this->gender = self::UNKNOWN;
        $this->titleFirmroles = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
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
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set streetAddress
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
     * Get streetAddress
     *
     * @return string
     */
    public function getStreetAddress() {
        return $this->streetAddress;
    }

    /**
     * Set startDate
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
     * Get startDate
     *
     * @return string
     */
    public function getStartDate() {
        if ($this->startDate === '0000-00-00') {
            return null;
        }
        return $this->startDate;
    }

    /**
     * Set endDate
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
     * Get endDate
     *
     * @return string
     */
    public function getEndDate() {
        if ($this->endDate === '0000-00-00') {
            return null;
        }
        return $this->endDate;
    }

    /**
     * Set finalcheck
     *
     * @param boolean $finalcheck
     *
     * @return Firm
     */
    public function setFinalcheck($finalcheck) {
        $this->finalcheck = $finalcheck;

        return $this;
    }

    /**
     * Get finalcheck
     *
     * @return boolean
     */
    public function getFinalcheck() {
        return $this->finalcheck;
    }

    /**
     * Set city
     *
     * @param Geonames $city
     *
     * @return Firm
     */
    public function setCity(Geonames $city = null) {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return Geonames
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Add titleFirmrole
     *
     * @param TitleFirmrole $titleFirmrole
     *
     * @return Firm
     */
    public function addTitleFirmrole(TitleFirmrole $titleFirmrole) {
        $this->titleFirmroles[] = $titleFirmrole;

        return $this;
    }

    /**
     * Remove titleFirmrole
     *
     * @param TitleFirmrole $titleFirmrole
     */
    public function removeTitleFirmrole(TitleFirmrole $titleFirmrole) {
        $this->titleFirmroles->removeElement($titleFirmrole);
    }

    /**
     * Get titleFirmroles for the firm, optionally sorted by name.
     *
     * @param boolean $sort
     *
     * @return Collection
     */
    public function getTitleFirmroles($sort = false) {
        if(! $sort) {
            return $this->titleFirmroles;
        }

        $iterator = $this->titleFirmroles->getIterator();
        $iterator->uasort(function(TitleFirmrole $a, TitleFirmrole $b) {
            return strcmp($a->getTitle()->getTitle(), $b->getTitle()->getTitle());
        });
        return new ArrayCollection(iterator_to_array($iterator));
    }

    /**
     * Get the name of the firm.
     *
     * @return string
     */
    public function __toString() {
        return $this->name;
    }

    /**
     * Set gender.
     *
     * @param string|null $gender
     *
     * @return Firm
     */
    public function setGender($gender = null)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender.
     *
     * @return string|null
     */
    public function getGender()
    {
        return $this->gender;
    }
}
