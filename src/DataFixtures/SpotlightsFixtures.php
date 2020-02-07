<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use joshtronic\LoremIpsum;
use Nines\BlogBundle\Entity\Post;
use Nines\BlogBundle\Entity\PostCategory;
use Nines\BlogBundle\Entity\PostStatus;

/**
 * Load some test spotlights.
 */
class SpotlightsFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
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
        $lipsum = new LoremIpsum();
        $status = $manager->getRepository(PostStatus::class)->findOneBy([
            'name' => 'published',
        ]);
        $user = $manager->getRepository(\Nines\UserBundle\Entity\User::class)->find(1);
        foreach (LoadSpotlightCategories::DATA as $data) {
            for ($i = 0; $i < 3; $i++) {
                $category = $manager->getRepository(PostCategory::class)->findOneBy([
                    'name' => $data['name'],
                ]);
                $post = new Post();
                $post->setCategory($category);
                $post->setTitle($lipsum->words(5));
                $post->setExcerpt($lipsum->paragraphs(1, 'p'));
                $post->setContent($lipsum->paragraphs(5, 'p'));
                $post->setStatus($status);
                $post->setUser($user);
                $manager->persist($post);
            }
        }
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() : array {
        return [
            SpotlightCategoriesFixtures::class,
            PostStatusFixtures::class,
        ];
    }
}
