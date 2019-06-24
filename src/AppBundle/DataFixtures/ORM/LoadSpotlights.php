<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use joshtronic\LoremIpsum;
use Nines\BlogBundle\DataFixtures\ORM\LoadPostStatus;
use Nines\BlogBundle\Entity\Post;
use Nines\BlogBundle\Entity\PostCategory;
use Nines\BlogBundle\Entity\PostStatus;

/**
 * Description of LoadSpotlightCategories
 *
 *
 */
class LoadSpotlights extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {

    public function load(ObjectManager $manager) {
        $lipsum = new LoremIpsum();
        $status = $manager->getRepository(PostStatus::class)->findOneBy(array(
            'name' => 'published',
        ));
        $user = $manager->getRepository(\Nines\UserBundle\Entity\User::class)->find(1);
        foreach (LoadSpotlightCategories::DATA as $data) {
            for ($i = 0; $i < 3; $i++) {
                $category = $manager->getRepository(PostCategory::class)->findOneBy(array(
                    'name' => $data['name'],
                ));
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

    public static function getGroups(): array {
        return array('setup');
    }

    public function getDependencies(): array {
        return array(
            LoadSpotlightCategories::class,
            LoadPostStatus::class,
        );
    }

}
