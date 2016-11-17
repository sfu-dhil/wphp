<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * Person
 *
 * @ORM\Table(name="person", 
 *  indexes={
 *      @ORM\Index(name="city_id_of_birth", columns={"city_id_of_birth"}), 
 *      @ORM\Index(name="city_id_of_death", columns={"city_id_of_death"}), 
 *      @ORM\Index(name="full", columns={"last_name", "first_name", "dob", "dod"}, flags={"fulltext"}), 
 *      @ORM\Index(name="last_name", columns={"last_name", "first_name", "title"}, flags={"fulltext"})
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonRepository")
 */
class Person
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=100, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=100, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=200, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1, nullable=true)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="dob", type="string", length=20, nullable=true)
     */
    private $dob;

    /**
     * @var string
     *
     * @ORM\Column(name="dod", type="string", length=20, nullable=true)
     */
    private $dod;

    /**
     * @var boolean
     *
     * @ORM\Column(name="checked", type="boolean", nullable=false)
     */
    private $checked = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="finalcheck", type="boolean", nullable=false)
     */
    private $finalcheck = '0';

    /**
     * @var \Geonames
     *
     * @ORM\ManyToOne(targetEntity="Geonames")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city_id_of_birth", referencedColumnName="geonameid")
     * })
     */
    private $cityOfBirth;

    /**
     * @var Geonames
     *
     * @ORM\ManyToOne(targetEntity="Geonames")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city_id_of_death", referencedColumnName="geonameid")
     * })
     */
    private $cityOfDeath;

	/**
	 * @var Collection|TitleRole[]
	 * @ORM\OneToMany(targetEntity="TitleRole", mappedBy="person")
	 */
	private $titleRoles;
	
	public function __construct() {
		$this->titleRoles = new ArrayCollection();
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
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Person
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Person
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Person
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Person
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set dob
     *
     * @param string $dob
     *
     * @return Person
     */
    public function setDob($dob)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * Get dob
     *
     * @return string
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * Set dod
     *
     * @param string $dod
     *
     * @return Person
     */
    public function setDod($dod)
    {
        $this->dod = $dod;

        return $this;
    }

    /**
     * Get dod
     *
     * @return string
     */
    public function getDod()
    {
        return $this->dod;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     *
     * @return Person
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * Get checked
     *
     * @return boolean
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Set finalcheck
     *
     * @param boolean $finalcheck
     *
     * @return Person
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
     * Set cityOfBirth
     *
     * @param Geonames $cityOfBirth
     *
     * @return Person
     */
    public function setCityOfBirth(Geonames $cityOfBirth = null)
    {
        $this->cityOfBirth = $cityOfBirth;

        return $this;
    }

    /**
     * Get cityOfBirth
     *
     * @return Geonames
     */
    public function getCityOfBirth()
    {
        return $this->cityOfBirth;
    }

    /**
     * Set cityOfDeath
     *
     * @param Geonames $cityOfDeath
     *
     * @return Person
     */
    public function setCityOfDeath(Geonames $cityOfDeath = null)
    {
        $this->cityOfDeath = $cityOfDeath;

        return $this;
    }

    /**
     * Get cityOfDeath
     *
     * @return Geonames
     */
    public function getCityOfDeath()
    {
        return $this->cityOfDeath;
    }

    /**
     * Add titleRole
     *
     * @param \AppBundle\Entity\TitleRole $titleRole
     *
     * @return Person
     */
    public function addTitleRole(\AppBundle\Entity\TitleRole $titleRole)
    {
        $this->titleRoles[] = $titleRole;

        return $this;
    }

    /**
     * Remove titleRole
     *
     * @param \AppBundle\Entity\TitleRole $titleRole
     */
    public function removeTitleRole(\AppBundle\Entity\TitleRole $titleRole)
    {
        $this->titleRoles->removeElement($titleRole);
    }

    /**
     * Get titleRoles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTitleRoles()
    {
        return $this->titleRoles;
    }
	
	public function __toString() {
		return $this->firstName . ' ' . $this->lastName;
	}
}
