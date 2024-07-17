<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FirmSource.
 */
#[ORM\Table(name: 'firm_source')]
#[ORM\Index(name: 'firm_source_identifier_idx', columns: ['identifier'])]
#[ORM\Index(name: 'firm_source_identifier_ft', columns: ['identifier'], flags: ['fulltext'])]
#[ORM\Entity]
class FirmSource {
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 300, nullable: true)]
    private ?string $identifier = null;

    #[ORM\ManyToOne(targetEntity: Firm::class, inversedBy: 'firmSources')]
    private ?Firm $firm = null;

    #[ORM\ManyToOne(targetEntity: Source::class, inversedBy: 'firmSources')]
    private ?Source $source = null;

    public function getId() : ?int {
        return $this->id;
    }

    public function setIdentifier(?string $identifier) : self {
        $this->identifier = $identifier;

        return $this;
    }

    public function getIdentifier() : ?string {
        return $this->identifier;
    }

    public function setFirm(?Firm $firm = null) : self {
        $this->firm = $firm;

        return $this;
    }

    public function getFirm() : ?Firm {
        return $this->firm;
    }

    public function setSource(?Source $source = null) : self {
        $this->source = $source;

        return $this;
    }

    public function getSource() : ?Source {
        return $this->source;
    }

    public function getIri() : ?string {
        $sourceId = $this->getSource()->getId();
        if ( ! in_array($sourceId, [30, 93, 100, 143], true)) {
            // Skip all but:
            // WorldCat (30)
            // British Book Trade Index (93)
            // London Metropolitan Archives Collections Catalogue (100)
            // Wikipedia (143)
            return null;
        }
        if ( ! $this->getIdentifier()) {
            return null;
        }
        if (preg_match('/https?:/', $this->getIdentifier())) {
            return $this->getIdentifier();
        }
        if (93 === $sourceId) {
            // British Book Trade Index
            $bbtiIdentifier = (int) $this->getIdentifier();
            if ($bbtiIdentifier) {
                return "http://bbti.bodleian.ox.ac.uk/details/?traderid={$bbtiIdentifier}";
            }
        }

        return null;
        /*
         * Skipped sources that don't really work (with some notes):
         *
         * 2 ESTC (57 records)
         * For books not firms
         *
         * 4 The English Novel 1830-1836 (1 record)
         * For books not firms (identifier is n/a)
         *
         * 5 ECCO (5 records)
         * For books not firms
         *
         * 7 British Library (1 record)
         * For books not firms
         *
         * 11 Jackson Bibliography (2 records)
         * For books not firms
         *
         * 13 Google Books (3 records)
         * For books not firms
         *
         * 34 None (2 records, 1 link and 1 n/a)
         * ???
         *
         * 75 American Antiquarian Society (14 records)
         * 1 link doesn't work, rest not sure how to link. not sure if books or firms
         *
         * 94 Nonconformist and Dissenting Women's Studies, 1650-1850 (2 records)
         * links don't work?
         *
         * 102 A Directory of Printers and Others in Allied Trades, London & Vicinity 1800-1840 (1 record)
         * Name?
         *
         * 114 Dictionary for Members of the Dublin Book Trade, 1550-1800 (1 record)
         * Name?
         *
         * 118 The Print Trade in Ireland 1550-1775 (5 records)
         * Names?
         *
         * 122 Thomas, Lucy and Henry Lasher Gardner, Opposite St. Clement’s Church in the Strand, 1739–1805 (1 record)
         * n/a
         *
         * 133 Plomer's Dictionary of Printers & Booksellers 1726 - 1775 (4 records)
         * Names?
         */
    }
}
