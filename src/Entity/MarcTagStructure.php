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
 * MarcTagStructure.
 *
 * @ORM\Table(name="marc_tag_structure",
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="marctag_tagfield_uniq", columns={"tagfield"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\MarcTagStructureRepository")
 */
class MarcTagStructure {
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
     * @ORM\Column(name="liblibrarian", type="string", length=255, nullable=false)
     */
    private $name;

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
     * @return MarcTagStructure
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
     * Set name.
     *
     * @param string $name
     *
     * @return MarcTagStructure
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
}
