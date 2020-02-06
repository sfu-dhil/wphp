<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TitleRole.
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
class TitleRole {
    /**
     * @var int
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
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title.
     *
     * @param \App\Entity\Title $title
     *
     * @return TitleRole
     */
    public function setTitle(Title $title = null) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return \App\Entity\Title
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set person.
     *
     * @param \App\Entity\Person $person
     *
     * @return TitleRole
     */
    public function setPerson(Person $person = null) {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person.
     *
     * @return \App\Entity\Person
     */
    public function getPerson() {
        return $this->person;
    }

    /**
     * Set role.
     *
     * @param \App\Entity\Role $role
     *
     * @return TitleRole
     */
    public function setRole(Role $role = null) {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role.
     *
     * @return \App\Entity\Role
     */
    public function getRole() {
        return $this->role;
    }
}
