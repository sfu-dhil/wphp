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
 *         @ORM\Index(name="aasmarc_data_idx", columns={"field_data"}, options={"lengths": {24} }),
 *         @ORM\Index(name="aasmarc_field_idx", columns={"field"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\AasMarcRepository")
 */
class AasMarc {
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="cid", type="integer")
     */
    private $titleId;

    /**
     * @var string
     *
     * @ORM\Column(name="field", type="string", length=3)
     */
    private $field;

    /**
     * @var string
     *
     * @ORM\Column(name="subfield", type="string", length=1, nullable=true)
     */
    private $subfield;

    /**
     * @var string
     *
     * @ORM\Column(name="field_data", type="text")
     */
    private $fieldData;

    /**
     * Return the field and subfield for this MARC record.
     */
    public function __toString() : string {
        return $this->field . $this->subfield;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set titleId.
     *
     * @param int $titleId
     *
     * @return AasMarc
     */
    public function setTitleId($titleId) {
        $this->titleId = $titleId;

        return $this;
    }

    /**
     * Get titleId.
     *
     * @return int
     */
    public function getTitleId() {
        return $this->titleId;
    }

    /**
     * Set field.
     *
     * @param string $field
     *
     * @return AasMarc
     */
    public function setField($field) {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field.
     *
     * @return string
     */
    public function getField() {
        return $this->field;
    }

    /**
     * Set subfield.
     *
     * @param string $subfield
     *
     * @return AasMarc
     */
    public function setSubfield($subfield) {
        $this->subfield = $subfield;

        return $this;
    }

    /**
     * Get subfield.
     *
     * @return string
     */
    public function getSubfield() {
        return $this->subfield;
    }

    /**
     * Set fieldData.
     *
     * @param string $fieldData
     *
     * @return AasMarc
     */
    public function setFieldData($fieldData) {
        $this->fieldData = $fieldData;

        return $this;
    }

    /**
     * Get fieldData.
     *
     * @return string
     */
    public function getFieldData() {
        return $this->fieldData;
    }
}
