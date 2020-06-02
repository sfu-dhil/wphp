<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
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

    /**
     * @var Collection|TitleSource[]
     * @ORM\OneToMany(targetEntity="TitleSource", mappedBy="source")
     */
    private $titleSources;

    /**
     * Source constructor.
     */
    public function __construct() {
        $this->titleSources = new ArrayCollection();
    }

    /**
     * Return the name of this source.
     */
    public function __toString() : string {
        return $this->name;
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
     * @return Source
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
     * Set description.
     *
     * @param string $description
     *
     * @return Source
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set citation.
     *
     * @param string $citation
     *
     * @return Source
     */
    public function setCitation($citation) {
        $this->citation = $citation;

        return $this;
    }

    /**
     * Get citation.
     *
     * @return string
     */
    public function getCitation() {
        return $this->citation;
    }

    /**
     * Set onlineSource.
     *
     * @param string $onlineSource
     *
     * @return Source
     */
    public function setOnlineSource($onlineSource) {
        $this->onlineSource = $onlineSource;

        return $this;
    }

    /**
     * Get onlineSource.
     *
     * @return string
     */
    public function getOnlineSource() {
        return $this->onlineSource;
    }

    /**
     * Add titleSource.
     *
     * @param \App\Entity\TitleSource $titleSource
     *
     * @return Source
     */
    public function addTitleSource(TitleSource $titleSource) {
        $this->titleSources[] = $titleSource;

        return $this;
    }

    /**
     * Remove titleSource.
     *
     * @param \App\Entity\TitleSource $titleSource
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTitleSource(TitleSource $titleSource) {
        return $this->titleSources->removeElement($titleSource);
    }

    /**
     * Get titleSources.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTitleSources() {
        return $this->titleSources;
    }
}
