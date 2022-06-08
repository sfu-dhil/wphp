<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FirmSource.
 *
 * @ORM\Table(name="firm_source",
 *     indexes={
 *         @ORM\Index(name="firm_source_identifier_idx", columns={"identifier"}),
 *         @ORM\Index(name="firm_source_identifier_ft", columns={"identifier"}, flags={"fulltext"})
 *     })
 *     @ORM\Entity
 */
class FirmSource {
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
     * @var Firm
     * @ORM\ManyToOne(targetEntity="App\Entity\Firm", inversedBy="firmSources")
     */
    private $firm;

    /**
     * @var Source
     * @ORM\ManyToOne(targetEntity="App\Entity\Source", inversedBy="firmSources")
     */
    private $source;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set identifier.
     *
     * @param string $identifier
     *
     * @return FirmSource
     */
    public function setIdentifier($identifier) {
        $this->identifier = $identifier;

        return $this;
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
     * Set firm.
     *
     * @param null|\App\Entity\Firm $firm
     *
     * @return FirmSource
     */
    public function setFirm(?Firm $firm = null) {
        $this->firm = $firm;

        return $this;
    }

    /**
     * Get firm.
     *
     * @return null|\App\Entity\Firm
     */
    public function getFirm() {
        return $this->firm;
    }

    /**
     * Set source.
     *
     * @param null|\App\Entity\Source $source
     *
     * @return FirmSource
     */
    public function setSource(?Source $source = null) {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source.
     *
     * @return null|\App\Entity\Source
     */
    public function getSource() {
        return $this->source;
    }
}
