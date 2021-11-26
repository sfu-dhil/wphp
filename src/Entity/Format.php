<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Format.
 *
 * @ORM\Table(name="format")
 * @ORM\Entity(repositoryClass="App\Repository\FormatRepository")
 */
class Format {
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\Column(name="name", type="text", nullable=true)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(name="abbreviation", type="string", length=10, nullable=true)
     */
    private ?string $abbreviation = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description = null;

    /**
     * @var Collection|Title[]
     * @ORM\OneToMany(targetEntity="Title", mappedBy="format")
     */
    private $titles;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->titles = new ArrayCollection();
    }

    public function __toString() : string {
        return $this->name;
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function getName() : ?string {
        return $this->name;
    }

    public function setName(?string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getAbbreviation() : ?string {
        return $this->abbreviation;
    }

    public function setAbbreviation(?string $abbreviation) : self {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    public function getDescription() : ?string {
        return $this->description;
    }

    public function setDescription(?string $description) : self {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Title[]
     */
    public function getTitles() : Collection {
        return $this->titles;
    }

    public function addTitle(Title $title) : self {
        if ( ! $this->titles->contains($title)) {
            $this->titles[] = $title;
            $title->setFormat($this);
        }

        return $this;
    }

    public function removeTitle(Title $title) : self {
        if ($this->titles->removeElement($title)) {
            // set the owning side to null (unless already changed)
            if ($title->getFormat() === $this) {
                $title->setFormat(null);
            }
        }

        return $this;
    }
}
