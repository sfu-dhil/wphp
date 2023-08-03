<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\PostStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class PostStatusFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $manager) : void {
        for ($i = 1; $i <= 5; $i++) {
            $fixture = new PostStatus();
            $fixture->setName('Name ' . $i);
            $fixture->setLabel('Label ' . $i);
            $fixture->setDescription("<p>This is paragraph {$i}</p>");
            $fixture->setPublic(0 === $i % 2);
            $manager->persist($fixture);
            $this->setReference('poststatus.' . $i, $fixture);
        }
        $manager->flush();
    }
}
