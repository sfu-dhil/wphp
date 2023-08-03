<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\PostCategory;
use App\Entity\PostStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Load some test spotlights.
 */
class SpotlightsFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['setup'];
    }

    public function load(ObjectManager $manager) : void {
        $status = $manager->getRepository(PostStatus::class)->findOneBy([
            'name' => 'published',
        ]);
        $user = $manager->getRepository(\Nines\UserBundle\Entity\User::class)->find(1);

        foreach (SpotlightCategoriesFixtures::DATA as $data) {
            for ($i = 0; $i < 3; $i++) {
                $category = $manager->getRepository(PostCategory::class)->findOneBy([
                    'name' => $data['name'],
                ]);
                $post = new Post();
                $post->setCategory($category);
                $post->setTitle("Title {$i} {$data['name']}");
                $post->setExcerpt("Excerpt {$i} {$data['name']}");
                $post->setContent("Paragraph {$i} {$data['name']}");
                $post->setStatus($status);
                $post->setUser($user);
                $manager->persist($post);
            }
        }
        $manager->flush();
    }

    public function getDependencies() : array {
        return [
            SpotlightCategoriesFixtures::class,
            PostStatusFixtures::class,
        ];
    }
}
