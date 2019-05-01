<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TitleSource
 *
 * @ORM\Table(name="title_source",
 *  indexes={
 *      @ORM\Index(name="title_source_identifier_idx", columns={"identifier"})
 * })
 * @ORM\Entity
 */
class TitleSource {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $identifier;

    /**
     * @var Title
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Title", inversedBy="titleSources")
     */
    private $title;

    /**
     * @var Source
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Source", inversedBy="titleSources")
     */
    private $source;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set identifier.
     *
     * @param string $identifier
     *
     * @return TitleSource
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set title.
     *
     * @param \AppBundle\Entity\Title|null $title
     *
     * @return TitleSource
     */
    public function setTitle(\AppBundle\Entity\Title $title = null)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return \AppBundle\Entity\Title|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set source.
     *
     * @param \AppBundle\Entity\Source|null $source
     *
     * @return TitleSource
     */
    public function setSource(\AppBundle\Entity\Source $source = null)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source.
     *
     * @return \AppBundle\Entity\Source|null
     */
    public function getSource()
    {
        return $this->source;
    }
}
