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
 * MarcSubfieldStructure.
 *
 * @ORM\Table(name="marc_subfield_structure",
 *     indexes={
 *         @ORM\Index(name="marcsubfield_fields_idx", columns={"tagfield", "tagsubfield"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\MarcSubfieldStructureRepository")
 */
class MarcSubfieldStructure {
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\Column(name="tagfield", type="string", length=3)
     */
    private string $tagField;

    /**
     * @ORM\Column(name="tagsubfield", type="string", length=1)
     */
    private string $tagSubfield;

    /**
     * @ORM\Column(name="liblibrarian", type="string", length=255, nullable=false)
     */
    private string $name;

    /**
     * @ORM\Column(name="isurl", type="boolean", nullable=true)
     */
    private ?bool $isUrl = null;

    /**
     * @ORM\Column(name="hidden", type="boolean", nullable=true)
     */
    private ?bool $hidden = null;

    public function __toString() : string {
        return $this->name;
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function getTagField() : ?string {
        return $this->tagField;
    }

    public function setTagField(string $tagField) : self {
        $this->tagField = $tagField;

        return $this;
    }

    public function getTagSubfield() : ?string {
        return $this->tagSubfield;
    }

    public function setTagSubfield(string $tagSubfield) : self {
        $this->tagSubfield = $tagSubfield;

        return $this;
    }

    public function getName() : ?string {
        return $this->name;
    }

    public function setName(string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getIsUrl() : ?bool {
        return $this->isUrl;
    }

    public function setIsUrl(?bool $isUrl) : self {
        $this->isUrl = $isUrl;

        return $this;
    }

    public function getHidden() : ?bool {
        return $this->hidden;
    }

    public function setHidden(?bool $hidden) : self {
        $this->hidden = $hidden;

        return $this;
    }
}
