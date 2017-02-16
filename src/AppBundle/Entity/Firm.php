<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * Firm
 * 
 * Doctrine isn't able to manage the unique contstraint on this table at all. It
 * must be created manually:
 * 
 * CREATE UNIQUE INDEX `unique` ON firm (name(100), city, start_date, end_date);
 *
 * @ORM\Table(name="firm", 
 *  uniqueConstraints={
 *		@ORM\UniqueConstraint(name="unique", columns={"name", "city", "start_date", "end_date"})
 *	}, 
 *  indexes={
 *      @ORM\Index(name="city", columns={"city"}), 
 *      @ORM\Index(name="full", columns={"name", "street_address"}, flags={"fulltext"}), 
 *      @ORM\Index(name="firmname", columns={"name"}, flags={"fulltext"})}
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FirmRepository")
 */
class Firm
{
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
     * @ORM\Column(name="name", type="text", nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="street_address", type="text", nullable=true)
     */
    private $streetAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="start_date", type="string", length=20, nullable=true)
     */
    private $startDate;

    /**
     * @var string
     *
     * @ORM\Column(name="end_date", type="string", length=20, nullable=true)
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
     * @ORM\ManyToOne(targetEntity="Geonames")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city", referencedColumnName="geonameid")
     * })
     */
    private $city;
	
	/**
	 * @var Collection|TitleFirmrole[]
	 * @ORM\OneToMany(targetEntity="TitleFirmrole", mappedBy="firm")
	 */
	private $titleFirmroles;
	
	public function __construct() {
		$this->titleFirmroles = new ArrayCollection();
	}

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Firm
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set streetAddress
     *
     * @param string $streetAddress
     *
     * @return Firm
     */
    public function setStreetAddress($streetAddress)
    {
        $this->streetAddress = $streetAddress;

        return $this;
    }

    /**
     * Get streetAddress
     *
     * @return string
     */
    public function getStreetAddress()
    {
        return $this->streetAddress;
    }

    /**
     * Set startDate
     *
     * @param string $startDate
     *
     * @return Firm
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return string
     */
    public function getStartDate()
    {
		if($this->startDate === '0000-00-00') {
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
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return string
     */
    public function getEndDate()
    {
		if($this->endDate === '0000-00-00') {
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
    public function setFinalcheck($finalcheck)
    {
        $this->finalcheck = $finalcheck;

        return $this;
    }

    /**
     * Get finalcheck
     *
     * @return boolean
     */
    public function getFinalcheck()
    {
        return $this->finalcheck;
    }

    /**
     * Set city
     *
     * @param Geonames $city
     *
     * @return Firm
     */
    public function setCity(Geonames $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return Geonames
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Add titleFirmrole
     *
     * @param \AppBundle\Entity\TitleFirmrole $titleFirmrole
     *
     * @return Firm
     */
    public function addTitleFirmrole(\AppBundle\Entity\TitleFirmrole $titleFirmrole)
    {
        $this->titleFirmroles[] = $titleFirmrole;

        return $this;
    }

    /**
     * Remove titleFirmrole
     *
     * @param \AppBundle\Entity\TitleFirmrole $titleFirmrole
     */
    public function removeTitleFirmrole(\AppBundle\Entity\TitleFirmrole $titleFirmrole)
    {
        $this->titleFirmroles->removeElement($titleFirmrole);
    }

    /**
     * Get titleFirmroles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTitleFirmroles()
    {
        return $this->titleFirmroles;
    }
	
	public function __toString() {
		return $this->name;
	}
}
