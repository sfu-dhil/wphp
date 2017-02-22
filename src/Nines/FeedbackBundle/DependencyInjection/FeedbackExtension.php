<?php

namespace Nines\FeedbackBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class FeedbackExtension extends Extension {

    public function load(array $configs, ContainerBuilder $container) {
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('services.yml');
		
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $map = array();
        $container->setParameter('feedback.default_status', $config['default_status']);
        $container->setParameter('feedback.public_status', $config['public_status']);
        foreach($config['commenting'] as $routing) {
            $map[$routing['class']] = $routing['route'];
        }        
        $container->setParameter('feedback.routing', $map);
    }
}
