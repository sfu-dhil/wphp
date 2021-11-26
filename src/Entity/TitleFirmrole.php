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
 * TitleFirmrole.
 *
 * @ORM\Table(name="title_firmrole")
 * @ORM\Entity
 */
class TitleFirmrole {
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="Title", inversedBy="titleFirmroles")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="title_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private ?Title $title = null;

    /**
     * @ORM\ManyToOne(targetEntity="Firm", inversedBy="titleFirmroles")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="firm_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private ?Firm $firm = null;

    /**
     * @ORM\ManyToOne(targetEntity="Firmrole")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="firmrole_id", referencedColumnName="id", nullable=false)
     * })
     */
    private ?Firmrole $firmrole = null;

    /**
     * Get id.
     */
    public function getId() : int {
        return $this->id;
    }

    public function getTitle() : ?Title {
        return $this->title;
    }

    public function setTitle(?Title $title) : self {
        $this->title = $title;

        return $this;
    }

    public function getFirm() : ?Firm {
        return $this->firm;
    }

    public function setFirm(?Firm $firm) : self {
        $this->firm = $firm;

        return $this;
    }

    public function getFirmrole() : ?Firmrole {
        return $this->firmrole;
    }

    public function setFirmrole(?Firmrole $firmrole) : self {
        $this->firmrole = $firmrole;

        return $this;
    }
}
