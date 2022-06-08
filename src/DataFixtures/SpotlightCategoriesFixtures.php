<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\PostCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test spotlight categories.
 */
class SpotlightCategoriesFixtures extends Fixture implements FixtureGroupInterface {
    public const DATA = [
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
    ];

    /**
     * {@inheritdoc}
     */
    public static function getGroups() : array {
        return ['setup'];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager) : void {
        foreach (self::DATA as $data) {
            $category = $manager->getRepository(PostCategory::class)->findOneBy([
                'name' => $data['name'],
            ]);
            if ( ! $category) {
                $category = new PostCategory();
                $category->setLabel($data['label']);
                $category->setName($data['name']);
                $manager->persist($category);
            }
        }
        $manager->flush();
    }
}
