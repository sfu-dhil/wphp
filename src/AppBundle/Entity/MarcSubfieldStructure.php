<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MarcSubfieldStructure
 *
 * @ORM\Table(name="marc_subfield_structure")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MarcSubfieldStructureRepository")
 */
class MarcSubfieldStructure
{

    /**
     * @var integer
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
     * @var boolean
     *
     * @ORM\Column(name="isurl", type="boolean", nullable=true)
     */
    private $isUrl;

}
