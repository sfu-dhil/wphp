<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EstcFields
 *
 * @ORM\Table(name="estc_fields")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EstcMarcRepository")
 */
class EstcMarc
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
     * @var integer
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


}

