<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Title;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test titles.
 */
class TitleFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    /**
     * {@inheritdoc}
     */
    public static function getGroups() : array {
        return ['test'];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Title();
            $fixture->setTitle('Title ' . $i);
            $fixture->setEditionNumber($i + 1);
            $fixture->setSignedAuthor('SignedAuthor ' . $i);
            $fixture->setSurrogate('Surrogate ' . $i);
            $fixture->setPseudonym('Pseudonym ' . $i);
            $fixture->setImprint('Imprint ' . $i);
            $fixture->setSelfpublished(0 === $i % 2);
            $fixture->setPubdate(1775 + $i);
            $fixture->setDateOfFirstPublication(1770 + $i);
            $fixture->setSizeL($i + 10);
            $fixture->setSizeW($i + 6);
            $fixture->setEdition('Edition ' . $i);
            $fixture->setVolumes($i + 1);
            $fixture->setPagination('Pagination ' . $i);
            $fixture->setPricePound($i + 1);
            $fixture->setPriceShilling($i);
            $fixture->setPricePence($i);
            $fixture->setShelfmark('Shelfmark ' . $i);
            $fixture->setChecked(0 === $i % 2);
            $fixture->setFinalcheck(0 === $i % 2);
            $fixture->setNotes('Notes ' . $i);
            $fixture->setLocationofprinting($this->getReference('geonames.1'));
            $fixture->setFormat($this->getReference('format.1'));
            $fixture->setGenre($this->getReference('genre.1'));

            $manager->persist($fixture);
            $this->setReference('title.' . $i, $fixture);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface" above
        return [
            FirmFixtures::class,
            FormatFixtures::class,
            GenreFixtures::class,
            GeonamesFixtures::class,
            PersonFixtures::class,
            RoleFixtures::class,
            SourceFixtures::class,
        ];
    }
}
