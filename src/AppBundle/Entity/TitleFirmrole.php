<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TitleFirmrole
 *
 * @ORM\Table(name="title_firmrole",
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="title_id", columns={"title_id", "firmrole_id", "firm_id"})
 *  }
 * )
 * @ORM\Entity
 */
class TitleFirmrole {

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
     *   @ORM\JoinColumn(name="title_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $title;

    /**
     * @var \Firm
     *
     * @ORM\ManyToOne(targetEntity="Firm", inversedBy="titleFirmroles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="firm_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $firm;

    /**
     * @var \Firmrole
     *
     * @ORM\ManyToOne(targetEntity="Firmrole")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="firmrole_id", referencedColumnName="id", nullable=false)
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
     * @param Title $title
     *
     * @return TitleFirmrole
     */
    public function setTitle(Title $title = null) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return Title
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set firm
     *
     * @param Firm $firm
     *
     * @return TitleFirmrole
     */
    public function setFirm(Firm $firm = null) {
        $this->firm = $firm;

        return $this;
    }

    /**
     * Get firm
     *
     * @return Firm
     */
    public function getFirm() {
        return $this->firm;
    }

    /**
     * Set firmrole
     *
     * @param Firmrole $firmrole
     *
     * @return TitleFirmrole
     */
    public function setFirmrole(Firmrole $firmrole = null) {
        $this->firmrole = $firmrole;

        return $this;
    }

    /**
     * Get firmrole
     *
     * @return Firmrole
     */
    public function getFirmrole() {
        return $this->firmrole;
    }

}
