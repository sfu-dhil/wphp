<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nines\BlogBundle\Entity\PostCategory;

/**
 * Load some test spotlight categories.
 */
class LoadSpotlightCategories extends Fixture implements FixtureGroupInterface {

    const DATA = array(
        [
            'name' => 'person',
            'label' => 'Person Spotlights',
        ],
        [
            'name' => 'firm',
            'label' => 'Firm Spotlights',
        ],
        [
            'name' => 'title',
            'label' => 'Title Spotlights',
        ],
    );

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager) {
        foreach (self::DATA as $data) {
            $category = $manager->getRepository(PostCategory::class)->findOneBy(array(
                'name' => $data['name'],
            ));
            if (!$category) {
                $category = new PostCategory();
                $category->setLabel($data['label']);
                $category->setName($data['name']);
                $manager->persist($category);
            }
        }
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public static function getGroups(): array {
        return array('setup');
    }

}
