<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'source')]
#[ORM\Entity(repositoryClass: SourceRepository::class)]
class Source implements Stringable {
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(name: 'name', type: 'string', length: 100, nullable: false)]
    private string $name = '';

    #[ORM\Column(name: 'description', type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: 'citation', type: 'text', nullable: true)]
    private ?string $citation = null;

    #[ORM\Column(name: 'onlinesource', type: 'string', length: 200, nullable: true)]
    #[Assert\Url]
    private ?string $onlineSource;

    /**
     * @var Collection<int,TitleSource>
     */
    #[ORM\OneToMany(targetEntity: TitleSource::class, mappedBy: 'source')]
    private Collection|array $titleSources;

    /**
     * @var Collection<int,FirmSource>
     */
    #[ORM\OneToMany(targetEntity: FirmSource::class, mappedBy: 'source')]
    private Collection|array $firmSources;

    public function __construct() {
        $this->titleSources = new ArrayCollection();
        $this->firmSources = new ArrayCollection();
    }

    public function __toString() : string {
        return $this->name;
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function setName(string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getName() : string {
        return $this->name;
    }

    public function setDescription(?string $description) : self {
        $this->description = $description;

        return $this;
    }

    public function getDescription() : ?string {
        return $this->description;
    }

    public function setCitation(?string $citation) : self {
        $this->citation = $citation;

        return $this;
    }

    public function getCitation() : ?string {
        return $this->citation;
    }

    public function setOnlineSource(?string $onlineSource) : self {
        $this->onlineSource = $onlineSource;

        return $this;
    }

    public function getOnlineSource() : ?string {
        return $this->onlineSource;
    }

    public function addTitleSource(TitleSource $titleSource) : self {
        $this->titleSources[] = $titleSource;

        return $this;
    }

    public function removeTitleSource(TitleSource $titleSource) : bool {
        return $this->titleSources->removeElement($titleSource);
    }

    /**
     * @return Collection<int,TitleSource>
     */
    public function getTitleSources() : Collection {
        return $this->titleSources;
    }

    public function addFirmSource(FirmSource $firmSource) : self {
        $this->firmSources[] = $firmSource;

        return $this;
    }

    public function removeFirmSource(FirmSource $firmSource) : bool {
        return $this->firmSources->removeElement($firmSource);
    }

    /**
     * @return Collection<int,FirmSource>
     */
    public function getFirmSources() : Collection {
        return $this->firmSources;
    }
}
