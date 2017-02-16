<?php

namespace Nines\BlogBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function postNavMenu(FactoryInterface $factory, array $options)
    {
        $em = $this->container->get('doctrine')->getManager();
        
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(array(
            'class' => 'dropdown-menu',
        ));
        $menu->setAttribute('dropdown', true);
        
        $status = $em->getRepository('NinesBlogBundle:PostStatus')->findOneBy(array(
            'name' => $this->container->getParameter('nines_blog.published_status')
        ));
        $posts = $em->getRepository('NinesBlogBundle:Post')->findBy(
            array('status' => $status), 
            array('id' => 'DESC'),
            $this->container->getParameter('nines_blog.menu_posts')
        );
        foreach($posts as $post) {
            $menu->addChild($post->getTitle(), array(
                'route' => 'post_show', 
                'routeParameters' => array(
                    'id' => $post->getId(),
                )
            ));
        }
        $menu->addChild('divider', array(
            'label' => '',
        ));
        $menu['divider']->setAttributes(array(
            'role' => 'separator',
            'class' => 'divider',
        ));
        
        $menu->addChild('All Announcements', array(
            'route' => 'post_index',
        ));
        
        return $menu;
    }
    
    public function pageNavMenu(FactoryInterface $factory, array $options) {
        $em = $this->container->get('doctrine')->getManager();
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(array(
            'class' => 'dropdown-menu',
        ));
        $menu->setAttribute('dropdown', true);
        $pages = $em->getRepository('NinesBlogBundle:Page')->findBy(
            array('public' => true), 
            array('weight' => 'ASC', 'title' => 'ASC')
        );
        foreach($pages as $page) {
            $menu->addChild($page->getTitle(), array(
                'route' => 'page_show',
                'routeParameters' => array(
                    'id' => $page->getId(),
                )
            ));
        }
        return $menu;
    }
}
