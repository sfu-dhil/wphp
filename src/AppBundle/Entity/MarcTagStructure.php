<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MarcTagStructure
 *
 * @ORM\Table(name="marc_tag_structure",
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="marctag_tagfield_uniq", columns={"tagfield"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MarcTagStructureRepository")
 */
class MarcTagStructure
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
     * @ORM\Column(name="liblibrarian", type="string", length=255, nullable=false)
     */
    private $name;

}
