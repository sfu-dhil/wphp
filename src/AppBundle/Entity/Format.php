<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Format
 *
 * @ORM\Table(name="format", indexes={
 *  @ORM\Index(name="format_full_idx", columns={"abbrev1", "abbrev2", "abbrev3", "abbrev4"}, flags={"fulltext"})
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FormatRepository")
 */
class Format
{
    /**
     * @var boolean
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
     * @ORM\Column(name="abbrev1", type="string", length=10, nullable=true)
     */
    private $abbrevOne;

    /**
     * @var string
     *
     * @ORM\Column(name="abbrev2", type="string", length=10, nullable=true)
     */
    private $abbrevTwo;

    /**
     * @var string
     *
     * @ORM\Column(name="abbrev3", type="string", length=10, nullable=true)
     */
    private $abbrevThree;

    /**
     * @var string
     *
     * @ORM\Column(name="abbrev4", type="string", length=10, nullable=true)
     */
    private $abbrevFour;

    /**
     * @var Collection|Title[]
     * @ORM\OneToMany(targetEntity="Title", mappedBy="format")
     */
    private $titles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->titles = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return boolean
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Format
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
     * Set abbrevOne
     *
     * @param string $abbrevOne
     *
     * @return Format
     */
    public function setAbbrevOne($abbrevOne) {
        $this->abbrevOne = $abbrevOne;

        return $this;
    }

    /**
     * Get abbrevOne
     *
     * @return string
     */
    public function getAbbrevOne() {
        return $this->abbrevOne;
    }

    /**
     * Set abbrevTwo
     *
     * @param string $abbrevTwo
     *
     * @return Format
     */
    public function setAbbrevTwo($abbrevTwo) {
        $this->abbrevTwo = $abbrevTwo;

        return $this;
    }

    /**
     * Get abbrevTwo
     *
     * @return string
     */
    public function getAbbrevTwo() {
        return $this->abbrevTwo;
    }

    /**
     * Set abbrevThree
     *
     * @param string $abbrevThree
     *
     * @return Format
     */
    public function setAbbrevThree($abbrevThree) {
        $this->abbrevThree = $abbrevThree;

        return $this;
    }

    /**
     * Get abbrevThree
     *
     * @return string
     */
    public function getAbbrevThree() {
        return $this->abbrevThree;
    }

    /**
     * Set abbrevFour
     *
     * @param string $abbrevFour
     *
     * @return Format
     */
    public function setAbbrevFour($abbrevFour) {
        $this->abbrevFour = $abbrevFour;

        return $this;
    }

    /**
     * Get abbrevFour
     *
     * @return string
     */
    public function getAbbrevFour() {
        return $this->abbrevFour;
    }

    /**
     * Add title
     *
     * @param Title $title
     *
     * @return Format
     */
    public function addTitle(Title $title)
    {
        $this->titles[] = $title;

        return $this;
    }

    /**
     * Remove title
     *
     * @param Title $title
     */
    public function removeTitle(Title $title)
    {
        $this->titles->removeElement($title);
    }

    /**
     * Get titles
     *
     * @return Collection
     */
    public function getTitles()
    {
        return $this->titles;
    }
}
