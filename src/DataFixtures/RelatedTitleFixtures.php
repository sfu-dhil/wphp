<?php

namespace App\DataFixtures;

use App\Entity\RelatedTitle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RelatedTitleFixtures extends Fixture implements DependentFixtureInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new RelatedTitle();

            $fixture->setSourcetitle($this->getReference('title.' . $i));
            $fixture->setRelatedtitle($this->getReference('title.' . $i));
            $fixture->setTitlerelationship($this->getReference('titlerelationship.' . $i));
            $em->persist($fixture);
            $this->setReference('relatedtitle.' . $i, $fixture);
        }
        $em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        return [
            TitleFixtures::class,
            TitleRelationshipFixtures::class,
        ];
    }

}
