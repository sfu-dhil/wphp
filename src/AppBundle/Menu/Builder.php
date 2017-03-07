<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function navMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(array(
            'class' => 'dropdown-menu',
        ));
        $menu->setAttribute('dropdown', true);
        
        $menu->addChild('Titles', array(
            'route' => 'title_index',
        ));
        $menu->addChild('Persons', array(
            'route' => 'person_index',
        ));
        $menu->addChild('Firms', array(
            'route' => 'firm_index',
        ));        
        
        return $menu;
    }
}
