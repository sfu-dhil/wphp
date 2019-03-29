<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TitleSource
 *
 * @ORM\Table(name="title_source")
 * @ORM\Entity(readOnly=true, repositoryClass="AppBundle\Repository\TitleSourceRepository")
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
     * @var Title
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Title")
     */
    private $title;

    /**
     * @var Source
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Source")
     */
    private $source;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $identifier;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get identifier.
     *
     * @return string
     */
    public function getIdentifier() {
        return $this->identifier;
    }

    /**
     * Get title.
     *
     * @return \AppBundle\Entity\Title|null
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Get source.
     *
     * @return \AppBundle\Entity\Source|null
     */
    public function getSource() {
        return $this->source;
    }
}
