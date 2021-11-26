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
 * TitleRole.
 *
 * @todo clean the data, then add this constraint:
 * uniqueConstraints={
 *  ORM\UniqueConstraint(name="title_uq_idx", columns={"title_id", "role_id", "person_id"})
 * }
 *
 * @ORM\Table(name="title_role",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="titlerole_uniq", columns={"title_id", "person_id", "role_id"})
 *     }
 * )
 * @ORM\Entity
 */
class TitleRole {
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="Title", inversedBy="titleRoles")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="title_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private ?Title $title = null;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="titleRoles")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="person_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private ?Person $person = null;

    /**
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=false)
     * })
     */
    private ?Role $role = null;

    /**
     * Get id.
     */
    public function getId() : int {
        return $this->id;
    }

    public function getTitle() : ?Title {
        return $this->title;
    }

    public function setTitle(?Title $title) : self {
        $this->title = $title;

        return $this;
    }

    public function getPerson() : ?Person {
        return $this->person;
    }

    public function setPerson(?Person $person) : self {
        $this->person = $person;

        return $this;
    }

    public function getRole() : ?Role {
        return $this->role;
    }

    public function setRole(?Role $role) : self {
        $this->role = $role;

        return $this;
    }
}
