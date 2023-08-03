<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'title_firmrole')]
#[ORM\Entity]
class TitleFirmrole {
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\JoinColumn(name: 'title_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Title::class, inversedBy: 'titleFirmroles')]
    private ?Title $title = null;

    #[ORM\JoinColumn(name: 'firm_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Firm::class, inversedBy: 'titleFirmroles')]
    private ?Firm $firm = null;

    #[ORM\JoinColumn(name: 'firmrole_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\ManyToOne(targetEntity: Firmrole::class)]
    private ?Firmrole $firmrole = null;

    public function getId() : ?int {
        return $this->id;
    }

    public function setTitle(?Title $title = null) : self {
        $this->title = $title;

        return $this;
    }

    public function getTitle() : ?Title {
        return $this->title;
    }

    public function setFirm(?Firm $firm = null) : self {
        $this->firm = $firm;

        return $this;
    }

    public function getFirm() : ?Firm {
        return $this->firm;
    }

    public function setFirmrole(?Firmrole $firmrole = null) : self {
        $this->firmrole = $firmrole;

        return $this;
    }

    public function getFirmrole() : ?Firmrole {
        return $this->firmrole;
    }
}
