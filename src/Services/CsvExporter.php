<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Firm;
use App\Entity\Person;
use App\Entity\Title;

class CsvExporter {
    /**
     * @return string[]
     */
    public function firmHeaders() {
        return ['Firm ID', 'Name', 'Gender', 'Street Address', 'City', 'Start Date', 'End Date'];
    }

    /**
     * @return array<int,mixed>
     */
    public function firmRow(Firm $firm) {
        return [
            $firm->getId(),
            $firm->getName(),
            $firm->getGender(),
            $firm->getStreetAddress(),
            $firm->getCity() ? $firm->getCity()->getName() : '',
            preg_replace('/-00/', '', (string) $firm->getStartDate() ?? ''),
            preg_replace('/-00/', '', (string) $firm->getEndDate() ?? ''),
        ];
    }

    /**
     * @return string[]
     */
    public function personHeaders() {
        return ['Person ID', 'Last Name', 'First Name', 'Gender', 'Birth Date', 'Birth City', 'Death Date', 'Death City'];
    }

    /**
     * @return array<int,mixed>
     */
    public function personRow(Person $person) {
        return [
            $person->getId(),
            $person->getLastName(),
            $person->getFirstName(),
            $person->getGender(),
            $person->getDob(),
            $person->getCityOfBirth() ? $person->getCityOfBirth()->getName() : '',
            $person->getDod(),
            $person->getCityOfDeath() ? $person->getCityOfDeath()->getName() : '',
        ];
    }

    /**
     * @return string[]
     */
    public function titleHeaders() {
        return [
            'Title Id',
            'Title',
            'Signed Author',
            'Pseudonym',
            'Imprint',
            'Self Published',
            'Printing City',
            'Printing Country',
            'Printing Lat',
            'Printing Long',
            'Date',
            'Format',
            'Length',
            'Width',
            'Edition',
            'Volumes',
            'Pagination',
            'Sources',
            'Price Pound',
            'Price Shilling',
            'Price Pence',
            'Other Price',
            'Genre',
            'Shelf mark',
        ];
    }

    /**
     * @return array<int,mixed>
     */
    public function titleRow(Title $title) {
        $sources = [];
        foreach ($title->getTitleSources() as $source) {
            $sources[] = $source->getSource()->getName() . ' ' . $source->getIdentifier();
        }

        return [
            $title->getId(),
            $title->getTitle(),
            $title->getSignedAuthor(),
            $title->getPseudonym(),
            $title->getImprint(),
            $title->getSelfPublished() ? 'yes' : 'no',
            $title->getLocationOfPrinting() ? $title->getLocationOfPrinting()->getName() : '',
            $title->getLocationOfPrinting() ? $title->getLocationOfPrinting()->getCountry() : '',
            $title->getLocationOfPrinting() ? $title->getLocationOfPrinting()->getLatitude() : '',
            $title->getLocationOfPrinting() ? $title->getLocationOfPrinting()->getLongitude() : '',
            $title->getPubDate(),
            $title->getFormat() ? $title->getFormat()->getName() : '',
            $title->getSizeL(),
            $title->getSizeW(),
            $title->getEdition(),
            $title->getVolumes(),
            $title->getPagination(),
            implode('; ', $sources),
            $title->getPricePound(),
            $title->getPriceShilling(),
            $title->getPricePence(),
            $title->getOtherPrice() ? $title->getOtherCurrency()->format((float) $title->getOtherPrice() ?? 0) : '',
            implode('; ', $title->getGenres()->toArray()),
            $title->getShelfmark(),
        ];
    }
}
