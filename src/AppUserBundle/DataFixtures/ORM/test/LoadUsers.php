<?php

namespace AppBundle\DataFixtures\ORM\test;

use AppBundle\Tests\Utilities\AbstractDataFixture;
use AppUserBundle\Entity\User;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of LoadPlaces
 *
 * @author mjoyce
 */
class LoadUsers extends AbstractDataFixture implements OrderedFixtureInterface
{

    protected function doLoad(ObjectManager $manager) {
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setPlainPassword('abc123');
        $admin->setFullname('Admin');
        $admin->setInstitution('SFU');
        $admin->setEnabled(true);
        $admin->addRole('ROLE_ADMIN');
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPlainPassword('abc123');
        $user->setFullname('User');
        $user->setInstitution('SFU');
        $user->setEnabled(true);
        $manager->persist($user);
        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }

    protected function getEnvironments() {
        return ['test'];
    }

}
