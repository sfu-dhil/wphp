<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadPerson form.
 */
class LoadPerson extends Fixture implements DependentFixtureInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Person();
            $fixture->setLastName('LastName ' . $i);
            $fixture->setFirstName('FirstName ' . $i);
            $fixture->setTitle('TitleName ' . $i);
            $fixture->setGender('F');
            $fixture->setDob(1750 + $i);
            $fixture->setDod(1800 + $i);
            $fixture->setChecked($i % 2 === 0);
            $fixture->setFinalcheck($i % 2 === 0);
            $fixture->setCityofbirth($this->getReference('geonames.1'));
            $fixture->setCityofdeath($this->getReference('geonames.1'));

            $em->persist($fixture);
            $this->setReference('person.' . $i, $fixture);
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
            LoadGeonames::class,
        ];
    }

}
