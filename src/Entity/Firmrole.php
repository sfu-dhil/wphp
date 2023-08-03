<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\FirmroleRepository;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Table(name: 'firmrole')]
#[ORM\Entity(repositoryClass: FirmroleRepository::class)]
class Firmrole implements Stringable {
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(name: 'name', type: 'text', nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    public function __toString() : string {
        return $this->name;
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function setName(?string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getName() : ?string {
        return $this->name;
    }

    public function setDescription(?string $description = null) : self {
        $this->description = $description;

        return $this;
    }

    public function getDescription() : ?string {
        return $this->description;
    }
}
