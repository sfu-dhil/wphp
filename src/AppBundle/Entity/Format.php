<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Format
 *
 * @ORM\Table(name="format", indexes={@ORM\Index(name="full", columns={"abbrev_one", "abbrev_two", "abbrev_three", "abbrev_four"})})
 * @ORM\Entity
 */
class Format
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="id", type="boolean", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", length=16777215, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="abbrev_one", type="string", length=10, nullable=true)
     */
    private $abbrevOne;

    /**
     * @var string
     *
     * @ORM\Column(name="abbrev_two", type="string", length=10, nullable=true)
     */
    private $abbrevTwo;

    /**
     * @var string
     *
     * @ORM\Column(name="abbrev_three", type="string", length=10, nullable=true)
     */
    private $abbrevThree;

    /**
     * @var string
     *
     * @ORM\Column(name="abbrev_four", type="string", length=10, nullable=true)
     */
    private $abbrevFour;



    /**
     * Get id
     *
     * @return boolean
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
     * @return Format
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
     * Set abbrevOne
     *
     * @param string $abbrevOne
     *
     * @return Format
     */
    public function setAbbrevOne($abbrevOne)
    {
        $this->abbrevOne = $abbrevOne;

        return $this;
    }

    /**
     * Get abbrevOne
     *
     * @return string
     */
    public function getAbbrevOne()
    {
        return $this->abbrevOne;
    }

    /**
     * Set abbrevTwo
     *
     * @param string $abbrevTwo
     *
     * @return Format
     */
    public function setAbbrevTwo($abbrevTwo)
    {
        $this->abbrevTwo = $abbrevTwo;

        return $this;
    }

    /**
     * Get abbrevTwo
     *
     * @return string
     */
    public function getAbbrevTwo()
    {
        return $this->abbrevTwo;
    }

    /**
     * Set abbrevThree
     *
     * @param string $abbrevThree
     *
     * @return Format
     */
    public function setAbbrevThree($abbrevThree)
    {
        $this->abbrevThree = $abbrevThree;

        return $this;
    }

    /**
     * Get abbrevThree
     *
     * @return string
     */
    public function getAbbrevThree()
    {
        return $this->abbrevThree;
    }

    /**
     * Set abbrevFour
     *
     * @param string $abbrevFour
     *
     * @return Format
     */
    public function setAbbrevFour($abbrevFour)
    {
        $this->abbrevFour = $abbrevFour;

        return $this;
    }

    /**
     * Get abbrevFour
     *
     * @return string
     */
    public function getAbbrevFour()
    {
        return $this->abbrevFour;
    }
}
