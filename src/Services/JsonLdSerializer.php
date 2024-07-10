<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Genre;
use App\Entity\Format;
use App\Entity\Firm;
use App\Entity\Title;
use App\Entity\Person;
use App\Entity\Geonames;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg;
use EasyRdf\Graph;

class JsonLdSerializer {
    public function __construct(
        private EntityManagerInterface $em,
        private UrlGeneratorInterface $generator,
    ) {}

    public function toRDF(SchemaOrg\Type $jsonLd) : string {
        $graph = new Graph();
        $graph->parse(json_encode($jsonLd->toArray()), 'jsonld');
        return $graph->serialise('rdfxml');
    }

    public function simplifyArray(array $entityArray) : mixed {
        switch (count($entityArray)) {
            case 0: return null;
            case 1: return $entityArray[0];
            default: return array_unique($entityArray);
        }
    }

    public function getFirmStub(Firm $firm) : SchemaOrg\MultiTypedEntity {
        $sameAs = [];
        foreach ($firm->getFirmSources() as $firmSource) {
            $iri = $firmSource->getIri();
            if ($iri) {
                $sameAs []= $iri;
            }
        }

        $entity = new SchemaOrg\MultiTypedEntity();
        $entity->organization()
            ->identifier($this->generator->generate('firm_show', ['id' => $firm->getId()], UrlGeneratorInterface::ABSOLUTE_URL))
            ->sameAs($this->simplifyArray($sameAs))
        ;
        $entity->person();

        return $entity;
    }

    public function getFirm(Firm $firm) : SchemaOrg\MultiTypedEntity {
        $affiliation = [];
        foreach (array_merge($firm->getFirmsRelated()->toArray(), $firm->getRelatedFirms()->toArray()) as $relatedFirm) {
            $affiliation []= $this->getFirmStub($relatedFirm);
        }

        $members = [];
        foreach ($firm->getRelatedPeople() as $relatedPerson) {
            $members []= $this->getPersonStub($relatedPerson);
        }

        $entity = $this->getFirmStub($firm);
        $entity->organization()
            ->name($firm->getName())
            ->setProperty('foundingDate', $firm->getStartDate()) //->foundingDate() expects DatetimeInterface
            ->setProperty('dissolutionDate', $firm->getEndDate()) //->dissolutionDate() expects DatetimeInterface
            ->address(
                Schema::postalAddress()
                    ->streetAddress($firm->getStreetAddress())
                    ->addressLocality($firm->getCity()?->getName())
                    ->addressCountry($firm->getCity()?->getCountry())
            )
            ->location($this->getGeoname($firm->getCity()))
            ->description($firm->getNotes())
            ->members($this->simplifyArray($members))
        ;
        $entity->person()
            ->affiliation($this->simplifyArray($affiliation))
            ->gender($firm->getGenderString())
        ;

        return $entity;
    }

    public function getPersonStub(Person $person) : \Spatie\SchemaOrg\Person {
        $sameAs = [];
        if ($person->getViafUrl()) {
            $sameAs []= $person->getViafUrl();
        }
        if ($person->getWikipediaUrl()) {
            $sameAs []= $person->getWikipediaUrl();
        }
        return Schema::person()
            ->identifier($this->generator->generate('person_show', ['id' => $person->getId()], UrlGeneratorInterface::ABSOLUTE_URL))
            ->sameAs($this->simplifyArray($sameAs))
        ;
    }


    public function getPerson(Person $person) : \Spatie\SchemaOrg\Person {
        $memberOf = [];
        foreach ($person->getRelatedFirms() as $relatedFirm) {
            $memberOf []= $this->getFirmStub($relatedFirm);
        }

        $alternateName = [];
        foreach ($person->getTitleRoles() as $titleRole) {
            if ($titleRole->getTitle()->getPseudonym()) {
                $alternateName []= $titleRole->getTitle()->getPseudonym();
            }
        }

        return $this->getPersonStub($person)
            ->givenName($person->getFirstName())
            ->familyName($person->getLastName())
            ->honorificPrefix($person->getTitle())
            ->alternateName($this->simplifyArray($alternateName))
            ->gender($person->getGenderString())
            ->setProperty('birthDate', $person->getDob()) //->birthDate() expects DatetimeInterface
            ->birthPlace($this->getGeoname($person->getCityOfBirth()))
            ->setProperty('deathDate', $person->getDod()) //->deathDate() expects DatetimeInterface
            ->deathPlace($this->getGeoname($person->getCityOfDeath()))
            ->image($person->getImageUrl())
            ->description($person->getNotes())
            ->memberOf($this->simplifyArray($memberOf))
        ;
    }

    public function getTitleStub(Title $title) : SchemaOrg\MultiTypedEntity {
        $sameAs = [];
        foreach ($title->getTitleSources() as $titleSource) {
            $iri = $titleSource->getIri();
            if ($iri) {
                $sameAs []= $iri;
            }
        }

        $entity = new SchemaOrg\MultiTypedEntity();
        $entity->book()
            ->identifier($this->generator->generate('title_show', ['id' => $title->getId()], UrlGeneratorInterface::ABSOLUTE_URL))
            ->sameAs($this->simplifyArray($sameAs))
        ;
        $entity->product();

        return $entity;
    }

    public function getTitle(Title $title) : SchemaOrg\MultiTypedEntity {
        // $title->getPseudonym(); used in Person (list of person's alternative names)

        $numberOfPages = null;
        $hasPart = [];
        if ($title->getVolumes() > 1) {
            $paginationParts = preg_split('/;|,/', ($title->getPagination() ?? ''));

            for ($index = 0; $index < $title->getVolumes(); ++$index) {
                $volumeNumber = $index+1;
                $volumeNumberOfPages = null;
                if($index < count($paginationParts) && preg_match('/\d+/', $paginationParts[$index], $match)) {
                    $volumeNumberOfPages = (int) $match[0];
                }

                $entity = new SchemaOrg\MultiTypedEntity();
                $entity->publicationVolume()
                    // ->identifier($this->generator->generate('title_show', ['id' => $title->getId()], UrlGeneratorInterface::ABSOLUTE_URL) . "#volume={$volumeNumber}")
                    ->volumeNumber($volumeNumber)
                    // ->pagination($title->getPagination())
                ;
                $entity->book()
                    ->numberOfPages($volumeNumberOfPages)
                ;
                $hasPart []= $entity;
            }
        } else {
            if(preg_match('/\d+/', $title->getPagination(), $match)) {
                $numberOfPages = (int) $match[0];
            }
        }

        $genre = [];
        foreach ($title->getGenres() as $titleGenre) {
            $genre []= $titleGenre->getName();
        }

        $offeredBy = [];
        $provider = [];
        $publisher = [];
        $contributor = [];
        $author = [];
        $editor = [];
        $translator = [];
        $illustrator = [];
        $copyrightHolder = [];
        $manufacturer = [];
        foreach ($title->getTitleFirmroles() as $titleFirmRole) {
            $firmEntity = $this->getFirm($titleFirmRole->getFirm());
            $roleId = $titleFirmRole->getFirmrole()->getId();

            if ($roleId == 1) {
                // Unknown
                $contributor []= $firmEntity; //Unknown so go with more generic contributor
            } elseif ($roleId == 2) {
                // Publisher
                $publisher []= $firmEntity;
            } elseif ($roleId == 3) {
                // Printer
                $manufacturer []= $firmEntity;
                $provider []= $firmEntity;
            } elseif ($roleId == 4) {
                // Bookseller
                $offeredBy []= $firmEntity;
                $provider []= $firmEntity;
            }
        }
        foreach ($title->getTitleRoles() as $titleRole) {
            $personEntity = $this->getPerson($titleRole->getPerson());
            $roleId = $titleRole->getRole()->getId();

            if ($roleId == 1) {
                // Author
                $author []= $personEntity;
            } elseif ($roleId == 2) {
                // Publisher
                $publisher []= $personEntity;
            } elseif ($roleId == 3) {
                // Bookseller
                $provider []= $personEntity;
            } elseif ($roleId == 4) {
                // Printer
                $provider []= $personEntity;
            } elseif ($roleId == 5) {
                // Editor
                $editor []= $personEntity;
            } elseif ($roleId == 6) {
                // Editor
                $translator []= $personEntity;
            } elseif ($roleId == 7) {
                // Engraver
                $contributor []= $personEntity; // Engraver is harder to pin down so generic contributor
            } elseif ($roleId == 9) {
                // Introducer
                $contributor []= $personEntity; // Introducer is harder to pin down so generic contributor
            } elseif ($roleId == 10) {
                // Illustrator
                $illustrator []= $personEntity;
            } elseif ($roleId == 11) {
                // Compiler
                $contributor []= $personEntity; // Compiler is harder to pin down so generic contributor
            } elseif ($roleId == 12) {
                // Composer
                $contributor []= $personEntity; // Composer is harder to pin down so generic contributor
            } elseif ($roleId == 13) {
                // Contributor
                $contributor []= $personEntity;
            } elseif ($roleId == 16) {
                // Copyright Holder
                $copyrightHolder []= $personEntity;
            }
        }


        $offers = [];
        if ($title->getTotalPrice() && $title->getTotalPrice() > 0) {
            $offers []= Schema::offer()
                ->price($title->getTotalPrice())
                ->priceCurrency('GBX')
                ->description('Price in British Pence')
                ->serialNumber($title->getShelfmark())
                ->offeredBy($this->simplifyArray($offeredBy))
            ;
        }
        if ($title->getOtherPrice() && $title->getOtherCurrency()) {
            $currency = $title->getOtherCurrency();
            $offers []= Schema::offer()
                ->price($title->getOtherPrice())
                ->priceCurrency($currency->getCode())
                ->serialNumber($title->getShelfmark())
                ->offeredBy($this->simplifyArray($offeredBy))
            ;
        }

        $isSimilarTo = [];
        foreach (array_merge($title->getRelatedTitles()->toArray(), $title->getTitlesRelated()->toArray()) as $relatedTitle) {
            $isSimilarTo []= $this->getTitleStub($relatedTitle);
        }

        $description = '';
        if ($title->getNotes()) {
            $description .= "Notes: " . $title->getNotes();
        }
        if ($title->getSignedAuthor()) {
            $description .= "\nSigned Author: " .$title->getSignedAuthor();
        }
        if (!is_null($title->getSelfpublished()) && $title->getSelfpublished()) {
            $description .= "\nSelf-published";
        }
        if ($title->getImprint()) {
            $description .= "\nImprint: " .$title->getImprint();
        }
        if ($title->getColophon()) {
            $description .= "\nColophon: " .$title->getColophon();
        }
        if ($title->getDateOfFirstPublication()) {
            $description .= "\nDate of first publication: " .$title->getDateOfFirstPublication();
        }

        $entity = $this->getTitleStub($title);
        $entity->book()
            ->name($title->getTitle())
            ->bookEdition($title->getEdition())
            ->version($title->getEditionNumber())
            ->copyrightNotice($title->getCopyright())
            ->setProperty('datePublished', $title->getPubdate()) //->datePublished() expects DatetimeInterface
            ->numberOfPages($numberOfPages)
            ->hasPart($this->simplifyArray($hasPart))
            ->offers($offers)
            ->description($description)
            ->locationCreated($this->getGeoname($title->getLocationOfPrinting()))
            ->bookFormat($this->getFormat($title->getFormat()))
            ->genre($this->simplifyArray($genre))
            ->publisher($this->simplifyArray($publisher))
            ->provider($this->simplifyArray($provider))
            ->contributor($this->simplifyArray($contributor))
            ->author($this->simplifyArray($author))
            ->editor($this->simplifyArray($editor))
            ->translator($this->simplifyArray($translator))
            ->illustrator($this->simplifyArray($illustrator))
            ->copyrightHolder($this->simplifyArray($copyrightHolder))
        ;

        $entity->product()
            ->isSimilarTo($this->simplifyArray($isSimilarTo))
            ->size($title->getSizeString())
            ->width($title->getSizeWString())
            ->height($title->getSizeLString())
            ->manufacturer($this->simplifyArray($manufacturer))
        ;

        return $entity;
    }

    public function getFormat(?Format $format) : ?SchemaOrg\BookFormatType {
        if (!$format) {
            return null;
        }

        return Schema::bookFormatType()
            ->identifier($this->generator->generate('format_show', ['id' => $format->getId()], UrlGeneratorInterface::ABSOLUTE_URL))
            ->additionalType($format->getName())
            ->name($format->getName())
            ->alternateName($format->getAbbreviation())
            ->description($format->getDescription())
        ;
    }


    public function getGeonameStub(Geonames $geoname) : ?SchemaOrg\Place {
        return Schema::place()
            ->identifier($this->generator->generate('geonames_show', ['id' => $geoname->getGeonameid()], UrlGeneratorInterface::ABSOLUTE_URL))
            ->sameAs("https://www.geonames.org/{$geoname->getGeonameid()}");
    }

    public function getGeoname(?Geonames $geoname) : ?SchemaOrg\Place {
        if (!$geoname) {
            return null;
        }
        return $this->getGeonameStub($geoname)
            ->geo(
                Schema::geoCoordinates()
                    ->longitude($geoname->getLongitude())
                    ->latitude($geoname->getLatitude())
            )
            ->address(
                Schema::postalAddress()
                    ->addressLocality($geoname->getName())
                    ->addressCountry($geoname->getCountry())
            )
            ->name($geoname->getName());
    }
}
