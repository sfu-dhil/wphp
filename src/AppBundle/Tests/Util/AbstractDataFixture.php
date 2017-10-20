<?php

namespace AppBundle\Tests\Util;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This class is a wrapper around AbstractFixture. When loading data it checks
 * which environment is active, and then only loads fixtures for that
 * environment.
 *
 * http://stackoverflow.com/questions/11817971
 */
abstract class AbstractDataFixture extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Check if the class should load data, and then load it via the overridden
     * doLoad() method.
     */
    final public function load(ObjectManager $em) {
        // @var KernelInterface $kernel
        $kernel = $this->container->get('kernel');
        if (in_array($kernel->getEnvironment(), $this->getEnvironments())) {
            $this->doLoad($em);
        } else {
            $this->container->get('logger')->notice('skipped.');
        }
    }

    /**
     * {@inheritdoc}.
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}.
     */
    public function getOrder() {
        return 1;
    }

    /**
     * Load the data into the database.
     *
     * @param ObjectManager $manager
     */
    abstract protected function doLoad(ObjectManager $manager);

    /**
     * Fixtures use this function to return an array listing the environments
     * that they should be activated for.
     */
    abstract protected function getEnvironments();

}
