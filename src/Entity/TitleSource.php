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
    private ?string $identifier = null;

    #[ORM\ManyToOne(targetEntity: Title::class, inversedBy: 'titleSources')]
    private ?Title $title = null;

    #[ORM\ManyToOne(targetEntity: Source::class, inversedBy: 'titleSources')]
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

    public function getSource() : ?Source {
        return $this->source;
    }

    public function getIri() : ?string {
        if ( ! $this->getIdentifier()) {
            return null;
        }
        if (preg_match('/https?:/', $this->getIdentifier())) {
            return $this->getIdentifier();
        }

        $sourceId = $this->getSource()->getId();
        if (2 === $sourceId) {
            // ESTC
            return "http://estc.bl.uk/{$this->getIdentifier()}";
        }
        if (in_array($sourceId, [3, 4, 78, 79, 80, 81, 82, 83, 84], true)) {
            // TODO: Note I'm making up a iri for this
            // The English Novel 1770-1829
            // The English Novel 1830-1836
            // The English Novel, 1800–1829 & 1830–1836: Update 7 (August 2009–July 2020)
            // The English Novel, 1800–1829 Update 1 (Apr 2000–May 2001)
            // The English Novel, 1800–1829: Update 2 (June 2001–May 2002)
            // The English Novel, 1800–1829: Update 3 (June 2002–May 2003)
            // The English Novel, 1800–1829: Update 4 (June 2003–August 2004)
            // The English Novel, 1800–1829: Update 5 (August 2004–August 2005)
            // The English Novel, 1800–1829 & 1830–1836 : Update 6 (August 2005–August 2009)
            $tenIdentifier = str_replace(' ', '', $this->getIdentifier());

            return "The English Novel:{$tenIdentifier}";
        }
        if (5 === $sourceId) {
            // TODO: Note I cannot access the resource to check if it works. Semi based on the SourceLinker but without modifying the identifier the same way (only remove the `GALE|` part)
            // ECCO / Eighteenth Century Collections Online
            $galeIdentifier = str_replace('GALE|', '', $this->getIdentifier());

            return "http://link.galegroup.com/apps/doc/{$galeIdentifier}/ECCO";
        }
        if (7 === $sourceId) {
            // TODO: The website isn't up atm so just guessing that these links work
            // British Library
            return "http://explore.bl.uk/{$this->getIdentifier()}";
        }
        if (8 === $sourceId) {
            // TODO: Note I'm making up this link (can't find the proper way to ref identifier to a page)
            // Orlando
            return "http://orlando.cambridge.org/{$this->getIdentifier()}";
        }
        if (11 === $sourceId) {
            // Jackson Bibliography
            return "https://jacksonbibliography.library.utoronto.ca/search/details/{$this->getIdentifier()}";
        }
        if (12 === $sourceId) {
            // Hathi Trust Digital Library
            return "https://catalog.hathitrust.org/Record/{$this->getIdentifier()}";
        }
        if (13 === $sourceId) {
            // Google Books
            return "https://books.google.ca/books?id={$this->getIdentifier()}";
        }
        if (14 === $sourceId) {
            // TODO: Note I'm making up a iri for this
            // NSTC (Nineteenth Century Short Title Catalogue)
            return "NSTC:{$this->getIdentifier()}";
        }
        if (15 === $sourceId) {
            // TODO: Note I cannot access the resource to check if it works. based on ECCO (5)
            // NCCO (Nineteenth Century Collections Online)
            $galeIdentifier = str_replace('GALE|', '', $this->getIdentifier());

            return "http://link.galegroup.com/apps/doc/{$galeIdentifier}/NCCO";
        }
        if (20 === $sourceId && is_int($this->getIdentifier())) {
            // Stanford University Library
            return "https://searchworks.stanford.edu/view/{$this->getIdentifier()}";
        }
        if (23 === $sourceId) {
            // Osborne Collection of Early Children's Books
            $osborneIdentifier = str_replace('u', '', $this->getIdentifier());

            return "https://www.torontopubliclibrary.ca/detail.jsp?Ntt={$osborneIdentifier}";
        }
        if (28 === $sourceId) {
            // Internet Archive
            return "https://archive.org/details/{$this->getIdentifier()}";
        }
        if (28 === $sourceId) {
            // WorldCat
            return "http://www.worldcat.org/oclc/{$this->getIdentifier()}";
        }
        if (50 === $sourceId) {
            // Osborne Collection of Early Children's Books
            $btwIdentifier = str_replace('BTW', '', $this->getIdentifier());

            return "https://www.british-travel-writing.org/texts/{$btwIdentifier}";
        }
        if (56 === $sourceId) {
            // Bodleian Library
            return "http://solo.bodleian.ox.ac.uk/permalink/f/ds4uo7/oxfaleph{$this->getIdentifier()}";
        }
        if (75 === $sourceId) {
            // American Antiquarian Society
            $aasIdentifier = str_replace('Catalog Record #', '', $this->getIdentifier());

            return "https://catalog.mwa.org/vwebv/holdingsInfo?bibId={$aasIdentifier}";
        }
        if (132 === $sourceId) {
            // Baker Library, Harvard University
            return "http://id.lib.harvard.edu/alma/{$this->getIdentifier()}/catalog";
        }

        return null;
        /*
         * Skipped sources that don't really work (with some notes):
         *
         * 9 Chawton House Library (https://chawtonhouse.org/) (430 records).
         * Don't really have works directly accessible online expect for some pdfs (the identifier doesn't relate to the PDF urls). Not identifiers are links (but broken?)
         *
         * 10 Wordsworth Trust (https://wordsworth.org.uk/) (70 records).
         * the https://collections.wordsworth.org.uk/wtweb/home.asp page isn't working so can't generate links
         *
         * 16 Benjamin Tabart's Juvenile Library (Ed. Marjorie Moon, 1990) (5 records).
         * No idea what's happening here (is a single book but the identifier doesn't make sense to me). On https://www.worldcat.org
         *
         * 17 The Dartons (Lawrence Darton, 2004) (389 records).
         * No idea what's happening here.
         *
         * 18 New York Public Library (10 records)
         * 3 are real links, others can't be turned into iris ¯\_(ツ)_/¯
         *
         * 19 SFU Library - Bennett (14 records)
         * You can find the books by searching the identifier but it doesn't translate into an iri well ¯\_(ツ)_/¯
         *
         * 21 John Harris's Books for Youth, 1801–1843 (Ed. Marjorie Moon) (605 records).
         * Like 16 no idea what's happening here (is a single book but the identifier doesn't make sense to me). On https://www.worldcat.org
         *
         * 25 Devon Collection of Children's Books (Moon) (109 records).
         * Not sure how to work with this one ¯\_(ツ)_/¯
         *
         * 29 UNC University Libraries (16 records)
         * Not sure how to work with this (looks like a call number but nothing shows up?)
         *
         * 32 Bancroft Library, UC Berkeley
         * Like other Libraries, can't really work with the identifier
         *
         * 33 John Newbery and His Successors, 1750-1814 (S. Roscoe) (396 records)
         * Another worldcat book
         *
         * 35 Lewis Walpole Library (1 record)
         * Another library will call number like identifier
         *
         * 36 University of Colorado Boulder Digital Collections (1 record)
         *
         * 37 Irish Women Poets of the Romantic Period (2 records)
         *
         * 38 Toronto Public Library Website (10 records, only 2 without urls)
         *
         * 39 National Library of Scotland (4 records, only 2 without urls)
         *
         * 41 Toronto Public Library (60 records)
         * Another library will call number like identifier
         *
         * 47 Houghton Library (276 records)
         * Another library will call number like identifier
         *
         * 48 Bearden-White (246 records)
         * research page article, identifiers might be page numbers
         *
         * 51 University of British Columbia Rare Books and Special Collections (3 records)
         * Another library will call number like identifier
         *
         * 54 Folger Shakespeare Library (24 records)
         * Another library will call number like identifier
         *
         * 55 Widener Library (14 records)
         * Another library will call number like identifier
         *
         * 58 McGill Library's Chapbook Collection (1 record)
         *
         * 59 The Making of the Modern World (6 records)
         * Gale collection but no idea offhand what the collection url is to make the identifiers work
         *
         * 68 Slavery and Anti-Slavery: A Transnational Archive (15 records)
         * Another Gale collection but no idea offhand what the collection url is to make the identifiers work
         *
         * 69 Sabin Americana: History of the Americas 1500–1926 (51 records)
         * Another Gale collection but no idea offhand what the collection url is to make the identifiers work
         *
         * 71 A Bibliography of Eliza Haywood (44 records)
         * Book page numbers as identifiers
         *
         * 76 Early American Imprints: Evans TCP (22 records)
         * Not sure how to work with identifiers here
         *
         * 86 Oakland University Kresge Library - Hicks Collection (12 records, 3 non urls)
         * Another library will call number like identifier
         *
         * 88 America's Historical Imprints I: Evans, 1639–1800 (67 records)
         * Not sure how to with with this one
         *
         * 89 EEBO (5 records)
         * No source url and no clue how to generate urls
         *
         * 90 Frances Burney's Cecilia: A Publishing History (Catherine M. Parisian, 2012) (6 records)
         * No source url and no clue how to generate urls
         *
         * 92 Marketing a Sable Muse: Phillis Wheatley and the Antebellum Press (Jennifer Rene Young, 2011) (13 records)
         * No source url and no clue how to generate urls (looks like page numbers)
         *
         * 93 British Book Trade Index (1 record)
         * Doesn't work as a trader id in http://bbti.bodleian.ox.ac.uk/details/?traderid={ID}
         * For firms not books
         *
         * 97 Phillis Wheatley: A Bio-Bibliography (17 records)
         * No source url and no clue how to generate urls
         *
         * 98 America's Historical Imprints II: Shaw-Shoemaker, 1801-1819 (128 records)
         * No source url and no clue how to generate urls
         *
         * 99 The Minerva Press, 1790–1820 (Dorothy Blakey, 1939) (2 records)
         * No source url (looks like page numbers)
         *
         * 117 Boston Athenaeum (only 1 record)
         * Another library will call number like identifier
         *
         * 119 John Carter Brown Library (18 records)
         * Another library will call number like identifier
         *
         * 121 University of Alberta - Bruce Peel Special Collections (8 records)
         * Another library will call number like identifier
         *
         * 123 Princeton University Library Special Collections (15 records)
         * No source url and no clue how to generate urls (looks like call numbers)
         *
         * 124 Library Company of Philadelphia (1 record)
         * Another library. not sure what the number is (call or access number don't work)
         *
         * 127 The Huntington Library (5 records, 3 non-urls)
         * Another library will call number like identifier
         *
         * 128 Duke University Library (1 record)
         * Another library will call number like identifier
         *
         * 129 Longman Group Archive, University of Reading (3 records)
         * Another library will call number like identifier
         *
         * 131 The National Archives (6 records, 4 non-urls)
         * Looks like page numbers
         *
         * 133 Plomer's Dictionary of Printers & Booksellers 1726 - 1775 (2 records)
         * Names?
         *
         * 136 Mary Cooper, Eighteenth Century London Bookseller: A Bibliography (2 records)
         * referencing letters?
         *
         * 137 Digital Miscellanies Index (2 records)
         * DMI number but I don't want to manually do the urls
         *
         * 138 Huntington Library (7 records)
         * No source url and no clue how to generate urls
         *
         * 140 William Andrews Clark Memorial Library (1 record)
         * Looks like a call number
         *
         * 141 The Making of Modern Law (1 record)
         * Gale but no idea on collection url
         *
         * 144 The Morgan Library & Museum (1 record)
         * Call number?
         *
         * 145 University of Glasgow Archives & Special Collections (1 record)
         * Call number?
         *
         * 146 The Lilly Library, Indiana University (1 record)
         * Call number?
         */
    }
}
