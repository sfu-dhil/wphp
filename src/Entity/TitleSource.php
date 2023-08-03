<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'title_source')]
#[ORM\Index(name: 'title_source_identifier_idx', columns: ['identifier'])]
#[ORM\Index(name: 'title_source_identifier_ft', columns: ['identifier'], flags: ['fulltext'])]
#[ORM\Entity]
class TitleSource {
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 300, nullable: true)]
    private string $identifier;

    #[ORM\ManyToOne(targetEntity: Title::class, inversedBy: 'titleSources')]
    private ?Title $title = null;

    #[ORM\ManyToOne(targetEntity: Source::class, inversedBy: 'titleSources')]
    private ?Source $source = null;

    public function getId() : ?int {
        return $this->id;
    }

    public function setIdentifier(string $identifier) : self {
        $this->identifier = $identifier;

        return $this;
    }

    public function getIdentifier() : string {
        return $this->identifier;
    }

    public function setTitle(?Title $title = null) : self {
        $this->title = $title;

        return $this;
    }

    public function getTitle() : ?Title {
        return $this->title;
    }

    public function setSource(?Source $source = null) : self {
        $this->source = $source;

        return $this;
    }

    public function getSource() : Source {
        return $this->source;
    }
}
