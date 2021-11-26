<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TitleSource.
 *
 * @ORM\Table(name="title_source",
 *     indexes={
 *         @ORM\Index(name="title_source_identifier_idx", columns={"identifier"}),
 *         @ORM\Index(name="title_source_identifier_ft", columns={"identifier"}, flags={"fulltext"})
 *     })
 *     @ORM\Entity
 */
class TitleSource {
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private ?string $identifier = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Title", inversedBy="titleSources")
     */
    private Title $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Source", inversedBy="titleSources")
     */
    private Source $source;

    /**
     * Get id.
     */
    public function getId() : int {
        return $this->id;
    }

    public function getIdentifier() : ?string {
        return $this->identifier;
    }

    public function setIdentifier(?string $identifier) : self {
        $this->identifier = $identifier;

        return $this;
    }

    public function getTitle() : ?Title {
        return $this->title;
    }

    public function setTitle(?Title $title) : self {
        $this->title = $title;

        return $this;
    }

    public function getSource() : ?Source {
        return $this->source;
    }

    public function setSource(?Source $source) : self {
        $this->source = $source;

        return $this;
    }
}
