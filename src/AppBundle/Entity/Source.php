<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Source
 *
 * @ORM\Table(name="source")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SourceRepository")
 *
 * @todo The Title entity refers to this one in three different ways, and may
 * do so in a fourth in future. Until that decision is made, there will not be
 * any back-references from Source to Title. The references will likely become
 * a many-to-many relationship.
 */
class Source
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
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="citation", type="text", nullable=true)
     */
    private $citation;

    /**
     * @var string
     * @ORM\Column(name="onlinesource", type="string", length=200, nullable=true)
     * @Assert\Url()
     */
    private $onlineSource;

    public function __toString() {
        return $this->name;
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
     * @return Source
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
     * Set description
     *
     * @param string $description
     *
     * @return Source
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set citation
     *
     * @param string $citation
     *
     * @return Source
     */
    public function setCitation($citation)
    {
        $this->citation = $citation;

        return $this;
    }

    /**
     * Get citation
     *
     * @return string
     */
    public function getCitation()
    {
        return $this->citation;
    }

    /**
     * Set onlineSource
     *
     * @param string $onlineSource
     *
     * @return Source
     */
    public function setOnlineSource($onlineSource)
    {
        $this->onlineSource = $onlineSource;

        return $this;
    }

    /**
     * Get onlineSource
     *
     * @return string
     */
    public function getOnlineSource()
    {
        return $this->onlineSource;
    }
}
