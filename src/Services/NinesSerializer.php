<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Firm;
use App\Entity\Format;
use App\Entity\Geonames;
use App\Entity\Person;
use App\Entity\Title;
use Doctrine\ORM\EntityManagerInterface;
use EasyRdf\Graph;
use EasyRdf\RdfNamespace;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NinesSerializer {
    public function __construct(
        private EntityManagerInterface $em,
        private UrlGeneratorInterface $generator,
    ) {}

    private function getPubYears(Title $title) : array {
        $results = [];
        if ($title->getPubdate()) {
            $century = null;
            preg_match_all('/(\d+)/', $title->getPubdate(), $matches);
            foreach($matches[1] as $match) {
                if (strlen($match) == 2 && $century) {
                    $match = "{$century}{$match}";
                }
                $results []= (int) $match;
                $century = substr($match, 0, 2);
            }
        }
        return $results;
    }

    private function publishedWithinYears($title, $startYear, $endYear) : bool {
        $pubYears = $this->getPubYears($title);
        foreach ($pubYears as $pubYear) {
            if ($pubYear >= $startYear && $pubYear <= $endYear) {
                return true;
            }
        }
        if (count($pubYears) == 2) {
            if ($pubYears[0] <= $startYear && $pubYears[1] >= $endYear) {
                return true;
            }
        }
        return false;
    }

    // 1785-1920
    public function isNines($title) : bool {
        return $this->publishedWithinYears($title, 1785, 1920);
    }

    // 1660-1830
    public function is18thConnect($title) : bool {
        return $this->publishedWithinYears($title, 1660, 1830);
    }

    public function getTitle(Title $title) : string {
        RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
        RdfNamespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
        RdfNamespace::set('role', 'http://www.loc.gov/loc.terms/relators/');
        RdfNamespace::set('dc', 'http://purl.org/dc/elements/1.1/');
        RdfNamespace::set('dcterms', 'http://purl.org/dc/terms/');
        RdfNamespace::set('collex', 'http://www.collex.org/schema#');
        RdfNamespace::set('wphp', 'https://womensprinthistoryproject.com/schema');

        $graph = new Graph();

        $titleRdf = $graph->resource($this->generator->generate('title_show', ['id' => $title->getId()], UrlGeneratorInterface::ABSOLUTE_URL), "wphp:Title");
        if ($this->is18thConnect($title)) {
            $titleRdf->add('collex:federation', '18thConnect'); // 1660-1830
        }
        if ($this->isNines($title)) {
            $titleRdf->add('collex:federation', 'NINES'); // 1785-1920
        }
        if (count($titleRdf->all('collex:federation')) == 0) {
            // add default collex
            $titleRdf->add('collex:federation', 'NINES');
        }
        $titleRdf->add('collex:archive', 'wphp');

        $titleRdf->add('dc:title', $title->getTitle());

        foreach ($title->getTitleSources() as $titleSource) {
            $iri = $titleSource->getIri();
            if ($iri) {
                $titleRdf->addResource('collex:source', $iri);
            }
        }


        foreach ($title->getTitleFirmroles() as $titleFirmRole) {
            $firmName = $titleFirmRole->getFirm()->getName();
            $roleId = $titleFirmRole->getFirmrole()->getId();

            if (1 === $roleId) {
                // Unknown
                $titleRdf->add('role:CTB', $firmName); // Unknown so go with more generic contributor
            } elseif (2 === $roleId) {
                // Publisher
                $titleRdf->add('role:PBL', $firmName);
            } elseif (3 === $roleId) {
                // Printer
                $titleRdf->add('role:PRT', $firmName);
            } elseif (4 === $roleId) {
                // Bookseller
                $titleRdf->add('role:BSL', $firmName);
            }
        }
        foreach ($title->getTitleRoles() as $titleRole) {
            $personName = (string) $titleRole->getPerson();
            $roleId = $titleRole->getRole()->getId();

            if (1 === $roleId) {
                // Author
                $titleRdf->add('role:AUT', $personName);
            } elseif (2 === $roleId) {
                // Publisher
                $titleRdf->add('role:PBL', $personName);
            } elseif (3 === $roleId) {
                // Bookseller
                $titleRdf->add('role:BSL', $personName);
            } elseif (4 === $roleId) {
                // Printer
                $titleRdf->add('role:PRT', $personName);
            } elseif (5 === $roleId) {
                // Editor
                $titleRdf->add('role:EDT', $personName);
            } elseif (6 === $roleId) {
                // Translator
                $titleRdf->add('role:TRL', $personName);
            } elseif (7 === $roleId) {
                // Engraver
                $titleRdf->add('role:EGR', $personName);
            } elseif (9 === $roleId) {
                // Introducer
                $titleRdf->add('role:WIN', $personName); // Writer of introduction
            } elseif (10 === $roleId) {
                // Illustrator
                $titleRdf->add('role:ILL', $personName);
            } elseif (11 === $roleId) {
                // Compiler
                $titleRdf->add('role:COM', $personName);
            } elseif (12 === $roleId) {
                // Composer
                $titleRdf->add('role:CMP', $personName);
            } elseif (13 === $roleId) {
                // Contributor
                $titleRdf->add('role:CTB', $personName);
            } elseif (16 === $roleId) {
                // Copyright Holder
                $titleRdf->add('role:CPH', $personName);
            }
        }
        if (count($titleRdf->all('role:AUT')) == 0) {
            // add default collex
            $titleRdf->add('role:AUT', 'Unknown');
        }

        $titleRdf->add('dc:type', 'Physical Object');

        foreach ($title->getGenres() as $titleGenre) {
            $genreId = $titleGenre->getId();

            if (in_array($genreId, [1, 2, 3, 4])) {
                // Fiction, Fiction Novel, Fiction Tale, Fiction Story
                $titleRdf->add('collex:discipline', 'Literature');
                $titleRdf->add('collex:genre', 'Fiction');
            } elseif (in_array($genreId, [5, 6, 7, 8, 9])) {
                // Poetry, Poetry Lyric, Poetry Collection, Poetry Dramatic, Poetry Epic
                $titleRdf->add('collex:discipline', 'Literature');
                $titleRdf->add('collex:genre', 'Poetry');
            } elseif (10 === $genreId) {
                // Drama
                $titleRdf->add('collex:discipline', 'Literature');
                $titleRdf->add('collex:genre', 'Drama');
            } elseif (11 === $genreId) {
                // Science/Natural History/Medicine
                $titleRdf->add('collex:discipline', 'Science');
            } elseif (12 === $genreId) {
                // Music
                $titleRdf->add('collex:discipline', 'Music Studies');
                $titleRdf->add('collex:genre', 'Music, Other');
            } elseif (13 === $genreId) {
                // Education
                $titleRdf->add('collex:discipline', 'Education');
            } elseif (14 === $genreId) {
                // Travel/Tourism/Topography
                $titleRdf->add('collex:discipline', 'Geography');
            } elseif (16 === $genreId) {
                // History
                $titleRdf->add('collex:discipline', 'History');
            } elseif (18 === $genreId) {
                // Juvenile Literature
                $titleRdf->add('collex:discipline', 'Literature');
            } elseif (19 === $genreId) {
                // Unknown (use defaults?)
            } elseif (21 === $genreId) {
                // Domestic ????
            } elseif (22 === $genreId) {
                // Letters
                $titleRdf->add('collex:genre', 'Correspondence');
            } elseif (23 === $genreId) {
                // Essays
                $titleRdf->add('collex:genre', 'Essay');
            } elseif (24 === $genreId) {
                // Biography
                $titleRdf->add('collex:discipline', 'History');
                $titleRdf->add('collex:genre', 'Nonfiction');
            } elseif (25 === $genreId) {
                // Memoirs
                $titleRdf->add('collex:discipline', 'History');
                $titleRdf->add('collex:genre', 'Nonfiction');
            } elseif (26 === $genreId) {
                // Fiction Romance
                $titleRdf->add('collex:discipline', 'Literature');
                $titleRdf->add('collex:genre', 'Fiction');
            } elseif (27 === $genreId) {
                // Works
                $titleRdf->add('collex:genre', 'Collection');
            } elseif (29 === $genreId) {
                // Religion/Biblical
                $titleRdf->add('collex:genre', 'Religion');
            } elseif (31 === $genreId) {
                // Miscellany
                $titleRdf->add('collex:genre', 'Collection');
            } elseif (38 === $genreId) {
                // Catalogue/Advertisement/Prospectus/Directory/List
                $titleRdf->add('collex:genre', 'Advertisement');
            } elseif (38 === $genreId) {
                // Catalogue/Advertisement/Prospectus/Directory/List
                $titleRdf->add('collex:genre', 'Advertisement');
            } elseif (40 === $genreId) {
                // Legal
                $titleRdf->add('collex:discipline', 'Law');
            } elseif (43 === $genreId) {
                // Annual Periodical ????
            } elseif (44 === $genreId) {
                // Literary Criticism/Critical Editions
                $titleRdf->add('collex:genre', 'Criticism');
            }
        }
        if (count($titleRdf->all('collex:discipline')) == 0) {
            // add default collex
            $titleRdf->add('collex:discipline', 'Literature');
        }
        if (count($titleRdf->all('collex:genre')) == 0) {
            // add default collex
            $titleRdf->add('collex:genre', 'Unspecified');
        }

        $titleRdf->add('collex:fulltext', 'FALSE');
        $titleRdf->add('dc:language', 'English');


        $pubYears = $this->getPubYears($title);
        if (count($pubYears) == 2 && $pubYears[0] < $pubYears[1]) {
            $dateRdf = $graph->newBNode('collex:date');
            $dateRdf->add('rdfs:label', $title->getPubdate());
            $dateRdf->add('rdfs:value', "{$pubYears[0]},{$pubYears[1]}");

            $titleRdf->add('dc:date', $dateRdf);
        } elseif (count($pubYears) == 1) {
            $titleRdf->add('dc:date', (string) $pubYears[0]);
        } else {
            $titleRdf->add('dc:date', 'Uncertain');
        }

        $titleRdf->add('collex:source_xml', $this->generator->generate('nines_title_show', ['id' => $title->getId()], UrlGeneratorInterface::ABSOLUTE_URL));
        $titleRdf->add('collex:source_html', $this->generator->generate('title_show', ['id' => $title->getId()], UrlGeneratorInterface::ABSOLUTE_URL));

        foreach (array_merge($title->getRelatedTitles()->toArray(), $title->getTitlesRelated()->toArray()) as $relatedTitle) {
            $titleRdf->addResource(
                'dc:relation',
                $this->generator->generate('title_show', ['id' => $relatedTitle->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
            );
        }

        $description = '';
        if ($title->getNotes()) {
            $description .= 'Notes: ' . $title->getNotes();
        }
        if ($title->getSignedAuthor()) {
            $description .= "\nSigned Author: " . $title->getSignedAuthor();
        }
        if (null !== $title->getSelfpublished() && $title->getSelfpublished()) {
            $description .= "\nSelf-published";
        }
        if ($title->getImprint()) {
            $description .= "\nImprint: " . $title->getImprint();
        }
        if ($title->getColophon()) {
            $description .= "\nColophon: " . $title->getColophon();
        }
        if ($title->getDateOfFirstPublication()) {
            $description .= "\nDate of first publication: " . $title->getDateOfFirstPublication();
        }
        if ($title->getLocationOfPrinting()) {
            $description .= "\nLocation of printing: " . (string) $title->getLocationOfPrinting();
        }
        $titleRdf->add('dc:description', $description);

        return $graph->serialise('rdfxml');
    }
}
