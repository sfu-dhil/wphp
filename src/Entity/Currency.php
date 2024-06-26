<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;
use NumberFormatter;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency extends AbstractEntity {
    /**
     * The 3-letter ISO 4217 currency code indicating the currency to use.
     *
     * @see https://en.wikipedia.org/wiki/ISO_4217
     * @see https://www.iso.org/iso-4217-currency-codes.html
     */
    #[ORM\Column(type: 'string', length: 3, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(type: 'string', length: 64, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 4, nullable: true)]
    private ?string $symbol = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int,Title>
     */
    #[ORM\OneToMany(targetEntity: Title::class, mappedBy: 'otherCurrency')]
    private array|Collection $titles;

    public function __construct() {
        parent::__construct();
        $this->titles = new ArrayCollection();
    }

    public function __toString() : string {
        return $this->name;
    }

    public function format(float $value) : bool|string {
        if ($this->code) {
            $fmt = new NumberFormatter('en_CA', NumberFormatter::CURRENCY);

            return $fmt->formatCurrency($value, $this->code);
        }
        $v = sprintf('%.2f', $value);
        if ($this->symbol) {
            return $this->symbol . $v;
        }

        return $this->name ? $v . ' ' . $this->name : $v;
    }

    public function getCode() : ?string {
        return $this->code;
    }

    public function setCode(?string $code) : self {
        $this->code = $code;

        return $this;
    }

    public function getName() : ?string {
        return $this->name;
    }

    public function setName(string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getSymbol() : ?string {
        return $this->symbol;
    }

    public function setSymbol(?string $symbol) : self {
        $this->symbol = $symbol;

        return $this;
    }

    public function getDescription() : ?string {
        return $this->description;
    }

    public function setDescription(?string $description) : self {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int,Title>
     */
    public function getTitles() : Collection {
        return $this->titles;
    }

    public function addTitle(Title $title) : self {
        if ( ! $this->titles->contains($title)) {
            $this->titles[] = $title;
            $title->setOtherCurrency($this);
        }

        return $this;
    }

    public function removeTitle(Title $title) : self {
        if ($this->titles->contains($title)) {
            $this->titles->removeElement($title);
            // set the owning side to null (unless already changed)
            if ($title->getOtherCurrency() === $this) {
                $title->setOtherCurrency(null);
            }
        }

        return $this;
    }
}
