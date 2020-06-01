<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MarcSubfieldStructure.
 *
 * @ORM\Table(name="marc_subfield_structure",
 *  indexes={
 *      @ORM\Index(name="marcsubfield_fields_idx", columns={"tagfield", "tagsubfield"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\MarcSubfieldStructureRepository")
 */
class MarcSubfieldStructure {
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tagfield", type="string", length=3)
     */
    private $tagField;

    /**
     * @var string
     *
     * @ORM\Column(name="tagsubfield", type="string", length=1)
     */
    private $tagSubfield;

    /**
     * @var string
     *
     * @ORM\Column(name="liblibrarian", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="isurl", type="boolean", nullable=true)
     */
    private $isUrl;

    /**
     * @var bool
     *
     * @ORM\Column(name="hidden", type="integer", nullable=true)
     */
    private $hidden;

    public function __toString() : string {
        return $this->name;
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
     * Set tagField.
     *
     * @param string $tagField
     *
     * @return MarcSubfieldStructure
     */
    public function setTagField($tagField) {
        $this->tagField = $tagField;

        return $this;
    }

    /**
     * Get tagField.
     *
     * @return string
     */
    public function getTagField() {
        return $this->tagField;
    }

    /**
     * Set tagSubfield.
     *
     * @param string $tagSubfield
     *
     * @return MarcSubfieldStructure
     */
    public function setTagSubfield($tagSubfield) {
        $this->tagSubfield = $tagSubfield;

        return $this;
    }

    /**
     * Get tagSubfield.
     *
     * @return string
     */
    public function getTagSubfield() {
        return $this->tagSubfield;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return MarcSubfieldStructure
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set isUrl.
     *
     * @param bool $isUrl
     *
     * @return MarcSubfieldStructure
     */
    public function setIsUrl($isUrl) {
        $this->isUrl = $isUrl;

        return $this;
    }

    /**
     * Get isUrl.
     *
     * @return bool
     */
    public function getIsUrl() {
        return $this->isUrl;
    }

    /**
     * Set hidden.
     *
     * @param int $hidden
     *
     * @return MarcSubfieldStructure
     */
    public function setHidden($hidden) {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Get hidden.
     *
     * @return int
     */
    public function getHidden() {
        return $this->hidden;
    }
}
