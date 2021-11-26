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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Source.
 *
 * @ORM\Table(name="source")
 * @ORM\Entity(repositoryClass="App\Repository\SourceRepository")
 */
class Source {
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private string $name;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\Column(name="citation", type="text", nullable=true)
     */
    private ?string $citation = null;

    /**
     * @ORM\Column(name="onlinesource", type="string", length=200, nullable=true)
     * @Assert\Url
     */
    private ?string $onlineSource = null;

    /**
     * @var Collection|TitleSource[]
     * @ORM\OneToMany(targetEntity="TitleSource", mappedBy="source")
     */
    private $titleSources;

    /**
     * @var Collection|FirmSource[]
     * @ORM\OneToMany(targetEntity="FirmSource", mappedBy="source")
     */
    private $firmSources;

    /**
     * Source constructor.
     */
    public function __construct() {
        $this->titleSources = new ArrayCollection();
        $this->firmSources = new ArrayCollection();
    }

    /**
     * Return the name of this source.
     */
    public function __toString() : string {
        return $this->name;
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function getName() : ?string {
        return $this->name;
    }

    public function setName(string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getDescription() : ?string {
        return $this->description;
    }

    public function setDescription(?string $description) : self {
        $this->description = $description;

        return $this;
    }

    public function getCitation() : ?string {
        return $this->citation;
    }

    public function setCitation(?string $citation) : self {
        $this->citation = $citation;

        return $this;
    }

    public function getOnlineSource() : ?string {
        return $this->onlineSource;
    }

    public function setOnlineSource(?string $onlineSource) : self {
        $this->onlineSource = $onlineSource;

        return $this;
    }

    /**
     * @return Collection|TitleSource[]
     */
    public function getTitleSources() : Collection {
        return $this->titleSources;
    }

    public function addTitleSource(TitleSource $titleSource) : self {
        if ( ! $this->titleSources->contains($titleSource)) {
            $this->titleSources[] = $titleSource;
            $titleSource->setSource($this);
        }

        return $this;
    }

    public function removeTitleSource(TitleSource $titleSource) : self {
        if ($this->titleSources->removeElement($titleSource)) {
            // set the owning side to null (unless already changed)
            if ($titleSource->getSource() === $this) {
                $titleSource->setSource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FirmSource[]
     */
    public function getFirmSources() : Collection {
        return $this->firmSources;
    }

    public function addFirmSource(FirmSource $firmSource) : self {
        if ( ! $this->firmSources->contains($firmSource)) {
            $this->firmSources[] = $firmSource;
            $firmSource->setSource($this);
        }

        return $this;
    }

    public function removeFirmSource(FirmSource $firmSource) : self {
        if ($this->firmSources->removeElement($firmSource)) {
            // set the owning side to null (unless already changed)
            if ($firmSource->getSource() === $this) {
                $firmSource->setSource(null);
            }
        }

        return $this;
    }
}
