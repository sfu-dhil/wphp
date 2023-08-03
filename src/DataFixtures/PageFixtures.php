<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Page;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Nines\UserBundle\DataFixtures\UserFixtures;

class PageFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $manager) : void {
        for ($i = 1; $i <= 5; $i++) {
            $fixture = new Page();
            $fixture->setInMenu(0 === $i % 2);
            $fixture->setWeight($i);
            $fixture->setPublic(0 === $i % 2);
            $fixture->setHomepage(1 === $i);
            $fixture->setIncludeComments(0 === $i % 2);
            $fixture->setTitle('Title ' . $i);
            $fixture->setExcerpt("<p>This is paragraph {$i}</p>");
            $fixture->setContent("<p>This is paragraph {$i}</p>");
            $fixture->setUser($this->getReference('user.inactive'));
            $manager->persist($fixture);
            $this->setReference('page.' . $i, $fixture);
        }
        $manager->flush();
    }

    /**
     * @return array<string>
     */
    public function getDependencies() : array {
        return [
            UserFixtures::class,
        ];
    }
}
