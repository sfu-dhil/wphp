<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TitleRole
 *
 * @todo clean the data, then add this constraint:
 * uniqueConstraints={
 *  ORM\UniqueConstraint(name="title_uq_idx", columns={"title_id", "role_id", "person_id"})
 * }
 *
 * @ORM\Table(name="title_role",
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="titlerole_uniq", columns={"title_id", "person_id", "role_id"})
 *  }
 * )
 * @ORM\Entity
 */
class TitleRole
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
     * @ORM\ManyToOne(targetEntity="Title", inversedBy="titleRoles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="title_id", referencedColumnName="id")
     * })
     */
    private $title;

    /**
     * @var \Person
     *
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="titleRoles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     * })
     */
    private $person;

    /**
     * @var \Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * })
     */
    private $role;



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
     * @return TitleRole
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
     * Set person
     *
     * @param \AppBundle\Entity\Person $person
     *
     * @return TitleRole
     */
    public function setPerson(\AppBundle\Entity\Person $person = null) {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \AppBundle\Entity\Person
     */
    public function getPerson() {
        return $this->person;
    }

    /**
     * Set role
     *
     * @param \AppBundle\Entity\Role $role
     *
     * @return TitleRole
     */
    public function setRole(\AppBundle\Entity\Role $role = null) {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \AppBundle\Entity\Role
     */
    public function getRole() {
        return $this->role;
    }
}
