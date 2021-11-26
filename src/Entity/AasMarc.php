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
 * AasFields.
 *
 * Field 035 $a \(MWA\)(\d) => https://catalog.mwa.org/vwebv/holdingsInfo?bibId=$1
 *
 * This table requires a manually-created index.
 *
 * The field_data column is text, but Doctrine isn't able to manage a non-fulltext
 * index on a text column. So create it manually.
 *
 * @ORM\Table(name="aas_fields",
 *     indexes={
 *         @ORM\Index(name="aasmarc_cid_idx", columns={"cid"}),
 *         @ORM\Index(name="aasmarc_data_ft", columns={"field_data"}, flags={"fulltext"}),
 *         @ORM\Index(name="aasmarc_data_idx", columns={"field_data"}, options={"lengths" = {24} }),
 *         @ORM\Index(name="aasmarc_field_idx", columns={"field"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\AasMarcRepository")
 */
class AasMarc {
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\Column(name="cid", type="integer")
     */
    private int $titleId;

    /**
     * @ORM\Column(name="field", type="string", length=3)
     */
    private string $field;

    /**
     * @ORM\Column(name="subfield", type="string", length=1, nullable=true)
     */
    private ?string $subfield = null;

    /**
     * @ORM\Column(name="field_data", type="text")
     */
    private string $fieldData;

    /**
     * Return the field and subfield for this MARC record.
     */
    public function __toString() : string {
        return $this->field . $this->subfield;
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function getTitleId() : ?int {
        return $this->titleId;
    }

    public function setTitleId(int $titleId) : self {
        $this->titleId = $titleId;

        return $this;
    }

    public function getField() : ?string {
        return $this->field;
    }

    public function setField(string $field) : self {
        $this->field = $field;

        return $this;
    }

    public function getSubfield() : ?string {
        return $this->subfield;
    }

    public function setSubfield(?string $subfield) : self {
        $this->subfield = $subfield;

        return $this;
    }

    public function getFieldData() : ?string {
        return $this->fieldData;
    }

    public function setFieldData(string $fieldData) : self {
        $this->fieldData = $fieldData;

        return $this;
    }
}
