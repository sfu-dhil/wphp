<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MarcTagStructureRepository;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Table(name: 'marc_tag_structure')]
#[ORM\UniqueConstraint(name: 'marctag_tagfield_uniq', columns: ['tagfield'])]
#[ORM\Entity(repositoryClass: MarcTagStructureRepository::class)]
class MarcTagStructure implements Stringable {
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(name: 'tagfield', type: 'string', length: 3)]
    private ?string $tagField = null;

    #[ORM\Column(name: 'liblibrarian', type: 'string', length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(name: 'hidden', type: 'integer', nullable: true)]
    private ?bool $hidden = false;

    public function __toString() : string {
        return $this->name;
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function setTagField(?string $tagField) : self {
        $this->tagField = $tagField;

        return $this;
    }

    public function getTagField() : ?string {
        return $this->tagField;
    }

    public function setName(?string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getName() : ?string {
        return $this->name;
    }

    public function setHidden(?bool $hidden) : self {
        $this->hidden = $hidden;

        return $this;
    }

    public function getHidden() : ?bool {
        return $this->hidden;
    }
}
