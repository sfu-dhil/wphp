<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AasMarcRepository;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

/**
 * AasFields.
 *
 * Field 035 $a \(MWA\)(\d) => https://catalog.mwa.org/vwebv/holdingsInfo?bibId=$1
 *
 * This table requires a manually-created index.
 *
 * The field_data column is text, but Doctrine isn't able to manage a non-fulltext
 * index on a text column. So create it manually.
 */
#[ORM\Table(name: 'aas_fields')]
#[ORM\Index(name: 'aasmarc_cid_idx', columns: ['cid'])]
#[ORM\Index(name: 'aasmarc_data_ft', columns: ['field_data'], flags: ['fulltext'])]
#[ORM\Index(name: 'aasmarc_data_idx', columns: ['field_data'], options: ['lengths' => [24]])]
#[ORM\Index(name: 'aasmarc_field_idx', columns: ['field'])]
#[ORM\Entity(repositoryClass: AasMarcRepository::class)]
class AasMarc implements Stringable {
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id;

    #[ORM\Column(name: 'cid', type: 'integer')]
    private ?int $titleId;

    #[ORM\Column(name: 'field', type: 'string', length: 3)]
    private ?string $field;

    #[ORM\Column(name: 'subfield', type: 'string', length: 1, nullable: true)]
    private ?string $subfield;

    #[ORM\Column(name: 'field_data', type: 'text')]
    private ?string $fieldData;

    public function __toString() : string {
        return $this->field . $this->subfield;
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function setTitleId(?int $titleId) : self {
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
