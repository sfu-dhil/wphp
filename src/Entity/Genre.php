<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Table(name: 'genre')]
#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre implements Stringable {
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(name: 'name', type: 'text', nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int,Title>
     */
    #[ORM\ManyToMany(targetEntity: Title::class, mappedBy: 'genres')]
    private array|Collection $titles;

    public function __construct() {
        $this->titles = new ArrayCollection();
    }

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

    public function addTitle(Title $title) : self {
        $this->titles[] = $title;

        return $this;
    }

    public function removeTitle(Title $title) : void {
        $this->titles->removeElement($title);
    }

    /**
     * @return Collection<int,Title>
     */
    public function getTitles() : Collection {
        return $this->titles;
    }

    public function setDescription(?string $description = null) : self {
        $this->description = $description;

        return $this;
    }

    public function getDescription() : ?string {
        return $this->description;
    }
}
