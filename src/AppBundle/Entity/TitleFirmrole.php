<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TitleFirmrole
 *
 * @ORM\Table(name="title_firmrole",
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="title_id", columns={"title_id", "firmrole_id", "firm_id"})},
 *  indexes={
 *      @ORM\Index(name="firm_id", columns={"firm_id"}),
 *      @ORM\Index(name="firmrole_id", columns={"firmrole_id"}),
 *      @ORM\Index(name="IDX_15768082A9F87BD", columns={"title_id"})
 * })
 * @ORM\Entity
 */
class TitleFirmrole
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Title
     *
     * @ORM\ManyToOne(targetEntity="Title", inversedBy="titleFirmroles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="title_id", referencedColumnName="id")
     * })
     */
    private $title;

    /**
     * @var \Firm
     *
     * @ORM\ManyToOne(targetEntity="Firm", inversedBy="titleFirmroles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="firm_id", referencedColumnName="id")
     * })
     */
    private $firm;

    /**
     * @var \Firmrole
     *
     * @ORM\ManyToOne(targetEntity="Firmrole")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="firmrole_id", referencedColumnName="id")
     * })
     */
    private $firmrole;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param \AppBundle\Entity\Title $title
     *
     * @return TitleFirmrole
     */
    public function setTitle(\AppBundle\Entity\Title $title = null) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return \AppBundle\Entity\Title
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set firm
     *
     * @param \AppBundle\Entity\Firm $firm
     *
     * @return TitleFirmrole
     */
    public function setFirm(\AppBundle\Entity\Firm $firm = null) {
        $this->firm = $firm;

        return $this;
    }

    /**
     * Get firm
     *
     * @return \AppBundle\Entity\Firm
     */
    public function getFirm() {
        return $this->firm;
    }

    /**
     * Set firmrole
     *
     * @param \AppBundle\Entity\Firmrole $firmrole
     *
     * @return TitleFirmrole
     */
    public function setFirmrole(\AppBundle\Entity\Firmrole $firmrole = null) {
        $this->firmrole = $firmrole;

        return $this;
    }

    /**
     * Get firmrole
     *
     * @return \AppBundle\Entity\Firmrole
     */
    public function getFirmrole() {
        return $this->firmrole;
    }
}
