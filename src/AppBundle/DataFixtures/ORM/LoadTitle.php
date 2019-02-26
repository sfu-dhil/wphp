<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Title;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadTitle form.
 */
class LoadTitle extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Title();
            $fixture->setTitle('Title ' . $i);
            $fixture->setEditionNumber($i+1);
            $fixture->setSignedAuthor('SignedAuthor ' . $i);
            $fixture->setSurrogate('Surrogate ' . $i);
            $fixture->setPseudonym('Pseudonym ' . $i);
            $fixture->setImprint('Imprint ' . $i);
            $fixture->setSelfpublished($i % 2 === 0);
            $fixture->setPubdate(1775 + $i);
            $fixture->setDateOfFirstPublication(1770 + $i);
            $fixture->setSizeL($i + 10);
            $fixture->setSizeW($i + 6);
            $fixture->setEdition('Edition ' . $i);
            $fixture->setVolumes($i+1);
            $fixture->setPagination('Pagination ' . $i);
            $fixture->setPricePound($i + 1);
            $fixture->setPriceShilling($i);
            $fixture->setPricePence($i);
            $fixture->setSourceId('SourceId ' . $i);
            $fixture->setSource2Id('Source2Id ' . $i);
            $fixture->setShelfmark('Shelfmark ' . $i);
            $fixture->setChecked($i % 2 === 0);
            $fixture->setFinalcheck($i % 2 === 0);
            $fixture->setNotes('Notes ' . $i);
            $fixture->setLocationofprinting($this->getReference('geonames.1'));
            $fixture->setFormat($this->getReference('format.1'));
            $fixture->setGenre($this->getReference('genre.1'));
            $fixture->setSource($this->getReference('source.1'));
            $fixture->setSource2($this->getReference('source.2'));

            $em->persist($fixture);
            $this->setReference('title.' . $i, $fixture);
        }

        $em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        // add dependencies here, or remove this 
        // function and "implements DependentFixtureInterface" above
        return [
            LoadFirm::class,
            LoadFormat::class,
            LoadGenre::class,
            LoadGeonames::class,
            LoadPerson::class,
            LoadRole::class,
            LoadSource::class,            
        ];
    }

    public static function getGroups(): array {
        return array('test');
    }
}
