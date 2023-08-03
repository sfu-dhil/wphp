<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OsborneMarcRepository;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

/**
 * This table requires a manually-created index.
 *
 * The field_data column is text, but Doctrine isn't able to manage a non-fulltext
 * index on a text column. So create it manually.
 */
#[ORM\Table(name: 'osborne_marc')]
#[ORM\Index(name: 'osborne_cid_idx', columns: ['cid'])]
#[ORM\Index(name: 'osborne_fielddata_ft', columns: ['field_data'], flags: ['fulltext'])]
#[ORM\Index(name: 'osborne_data_idx', columns: ['field_data'], options: ['lengths' => [24]])]
#[ORM\Index(name: 'osborne_field_idx', columns: ['field'])]
#[ORM\Entity(repositoryClass: OsborneMarcRepository::class)]
class OsborneMarc implements Stringable {
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(name: 'cid', type: 'integer')]
    private ?int $titleId = null;

    #[ORM\Column(name: 'field', type: 'string', length: 3)]
    private ?string $field = null;

    #[ORM\Column(name: 'subfield', type: 'string', length: 1, nullable: true)]
    private ?string $subfield = null;

    #[ORM\Column(name: 'field_data', type: 'text')]
    private ?string $fieldData = null;

    public function __toString() : string {
        return $this->fieldData;
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function setTitleId(?int $titleId) {
        $this->titleId = $titleId;

        return $this;
    }

    public function getTitleId() : ?int {
        return $this->titleId;
    }

    public function setField(?string $field) : self {
        $this->field = $field;

        return $this;
    }

    public function getField() : ?string {
        return $this->field;
    }

    public function setSubfield(?string $subfield) : self {
        $this->subfield = $subfield;

        return $this;
    }

    public function getSubfield() : ?string {
        return $this->subfield;
    }

    public function setFieldData(?string $fieldData) : self {
        $this->fieldData = $fieldData;

        return $this;
    }

    public function getFieldData() : ?string {
        return $this->fieldData;
    }
}
