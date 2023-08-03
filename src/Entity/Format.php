<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\FormatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Table(name: 'format')]
#[ORM\Entity(repositoryClass: FormatRepository::class)]
class Format implements Stringable {
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(name: 'name', type: 'text', nullable: true)]
    private ?string $name = null;

    #[ORM\Column(name: 'abbreviation', type: 'string', length: 10, nullable: true)]
    private ?string $abbreviation = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int,Title>
     */
    #[ORM\OneToMany(targetEntity: Title::class, mappedBy: 'format')]
    private Collection|array $titles;

    /**
     * Constructor.
     */
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

    public function setAbbreviation(?string $abbreviation) : self {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    public function getAbbreviation() : ?string {
        return $this->abbreviation;
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
