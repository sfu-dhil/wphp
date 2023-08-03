<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'title_role')]
#[ORM\UniqueConstraint(name: 'titlerole_uniq', columns: ['title_id', 'person_id', 'role_id'])]
#[ORM\Entity]
class TitleRole {
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\JoinColumn(name: 'title_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Title::class, inversedBy: 'titleRoles')]
    private ?Title $title = null;

    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'titleRoles')]
    private ?Person $person = null;

    #[ORM\JoinColumn(name: 'role_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\ManyToOne(targetEntity: Role::class)]
    private ?Role $role = null;

    public function getId() : ?int {
        return $this->id;
    }

    public function setTitle(?Title $title = null) : self {
        $this->title = $title;

        return $this;
    }

    public function getTitle() : ?Title {
        return $this->title;
    }

    public function setPerson(?Person $person = null) : self {
        $this->person = $person;

        return $this;
    }

    public function getPerson() : ?Person {
        return $this->person;
    }

    public function setRole(?Role $role = null) : self {
        $this->role = $role;

        return $this;
    }

    public function getRole() : ?Role {
        return $this->role;
    }
}
