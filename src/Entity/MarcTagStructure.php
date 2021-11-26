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
 * MarcTagStructure.
 *
 * @ORM\Table(name="marc_tag_structure",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="marctag_tagfield_uniq", columns={"tagfield"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\MarcTagStructureRepository")
 */
class MarcTagStructure {
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
     * @ORM\Column(name="liblibrarian", type="string", length=255, nullable=false)
     */
    private string $name;

    /**
     * @ORM\Column(name="hidden", type="boolean", nullable=true)
     */
    private ?bool $hidden;

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

    public function getName() : ?string {
        return $this->name;
    }

    public function setName(string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getHidden() : ?int {
        return $this->hidden;
    }

    public function setHidden(?int $hidden) : self {
        $this->hidden = $hidden;

        return $this;
    }
}
