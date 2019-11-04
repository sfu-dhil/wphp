<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Genre.
 *
 * @ORM\Table(name="genre")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GenreRepository")
 */
class Genre {
    /**
     * @var bool
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var Collection|Title[]
     * @ORM\OneToMany(targetEntity="Title", mappedBy="genre")
     */
    private $titles;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->titles = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return bool
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Genre
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
     * Add title.
     *
     * @param Title $title
     *
     * @return Genre
     */
    public function addTitle(Title $title) {
        $this->titles[] = $title;

        return $this;
    }

    /**
     * Remove title.
     *
     * @param Title $title
     */
    public function removeTitle(Title $title) {
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
     * Set description.
     *
     * @param null|string $description
     *
     * @return Format
     */
    public function setDescription($description = null) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return null|string
     */
    public function getDescription() {
        return $this->description;
    }
}
