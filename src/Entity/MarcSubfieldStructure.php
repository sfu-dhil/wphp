<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MarcSubfieldStructureRepository;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Table(name: 'marc_subfield_structure')]
#[ORM\Index(name: 'marcsubfield_fields_idx', columns: ['tagfield', 'tagsubfield'])]
#[ORM\Entity(repositoryClass: MarcSubfieldStructureRepository::class)]
class MarcSubfieldStructure implements Stringable {
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(name: 'tagfield', type: 'string', length: 3)]
    private ?string $tagField = null;

    #[ORM\Column(name: 'tagsubfield', type: 'string', length: 1)]
    private ?string $tagSubfield = null;

    #[ORM\Column(name: 'liblibrarian', type: 'string', length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(name: 'isurl', type: 'boolean', nullable: true)]
    private ?bool $isUrl = null;

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

    public function setTagSubfield(?string $tagSubfield) : self {
        $this->tagSubfield = $tagSubfield;

        return $this;
    }

    public function getTagSubfield() : ?string {
        return $this->tagSubfield;
    }

    public function setName(?string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getName() : ?string {
        return $this->name;
    }

    public function setIsUrl(?bool $isUrl) : self {
        $this->isUrl = $isUrl;

        return $this;
    }

    public function getIsUrl() : ?bool {
        return $this->isUrl;
    }

    public function setHidden(?bool $hidden) : self {
        $this->hidden = $hidden;

        return $this;
    }

    public function getHidden() : ?bool {
        return $this->hidden;
    }
}
