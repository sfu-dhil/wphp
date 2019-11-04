<?php

namespace AppBundle\Menu;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Nines\BlogBundle\Entity\Post;
use Nines\BlogBundle\Entity\PostCategory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Menu builder for the navigation and search menus.
 */
class Builder implements ContainerAwareInterface {
    use ContainerAwareTrait;

    // U+25BE, black down-pointing small triangle.
    const CARET = ' ▾';

    /**
     * List of spotlight menu items.
     *
     * @var array
     */
    private $spotlightMenuItems;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * Build the menu builder.
     *
     * @param array $spotlightMenuItems
     * @param EntityManagerInterface $em
     * @param FactoryInterface $factory
     * @param AuthorizationCheckerInterface $authChecker
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct($spotlightMenuItems, EntityManagerInterface $em, FactoryInterface $factory, AuthorizationCheckerInterface $authChecker, TokenStorageInterface $tokenStorage) {
        $this->spotlightMenuItems = $spotlightMenuItems;
        $this->em = $em;
        $this->factory = $factory;
        $this->authChecker = $authChecker;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Check if the current user is both logged in and granted a role.
     *
     * @param string $role
     *
     * @return bool
     */
    private function hasRole($role) {
        if ( ! $this->tokenStorage->getToken()) {
            return false;
        }

        return $this->authChecker->isGranted($role);
    }

    /**
     * Get the currently logged in user.
     *
     * @return null|object|string
     */
    private function getUser() {
        if ( ! $this->hasRole('ROLE_USER')) {
            return;
        }

        return $this->tokenStorage->getToken()->getUser();
    }

    /**
     * Build the navigation menu and return it.
     *
     * @param array $options
     *
     * @return ItemInterface
     */
    public function mainMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(array(
            'class' => 'nav navbar-nav',
        ));

        $browse = $menu->addChild('browse', array(
            'uri' => '#',
            'label' => 'Explore ' . self::CARET,
        ));
        $browse->setAttribute('dropdown', true);
        $browse->setLinkAttribute('class', 'dropdown-toggle');
        $browse->setLinkAttribute('data-toggle', 'dropdown');
        $browse->setChildrenAttribute('class', 'dropdown-menu');

        $browse->addChild('Titles', array(
            'route' => 'title_index',
        ));
        $browse->addChild('Persons', array(
            'route' => 'person_index',
        ));
        $browse->addChild('Firms', array(
            'route' => 'firm_index',
        ));

        $browse->addChild('Formats', array(
            'route' => 'format_index',
        ));
        $browse->addChild('Genres', array(
            'route' => 'genre_index',
        ));
        $browse->addChild('Sources', array(
            'route' => 'source_index',
        ));
        $browse->addChild('Contributor Roles', array(
            'route' => 'role_index',
        ));
        $browse->addChild('Firm Roles', array(
            'route' => 'firmrole_index',
        ));

        if ($this->hasRole('ROLE_USER')) {
            $divider = $browse->addChild('divider', array(
                'label' => '',
            ));
            $divider->setAttributes(array(
                'role' => 'separator',
                'class' => 'divider',
            ));
            $browse->addChild('Geonames', array(
                'route' => 'geonames_index',
            ));
            $browse->addChild('English Novel', array(
                'route' => 'resource_en_index',
            ));
            $browse->addChild('ESTC', array(
                'route' => 'resource_estc_index',
            ));
            $browse->addChild('Jackson', array(
                'route' => 'resource_jackson_index',
            ));
            $browse->addChild('Orlando', array(
                'route' => 'resource_orlando_biblio_index',
            ));
            $browse->addChild('Osborne', array(
                'route' => 'resource_osborne_index',
            ));
        }

        return $menu;
    }

    /**
     * Build the search menu and return it.
     *
     * @param array $options
     *
     * @return ItemInterface
     */
    public function searchMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(array(
            'class' => 'nav navbar-nav',
        ));

        $search = $menu->addChild('search', array(
            'uri' => '#',
            'label' => 'Search ' . self::CARET,
        ));
        $search->setAttribute('dropdown', true);
        $search->setLinkAttribute('class', 'dropdown-toggle');
        $search->setLinkAttribute('data-toggle', 'dropdown');
        $search->setChildrenAttribute('class', 'dropdown-menu');

        $search->addChild('Titles', array(
            'route' => 'title_search',
        ));
        $search->addChild('Persons', array(
            'route' => 'person_search',
        ));
        $search->addChild('Firms', array(
            'route' => 'firm_search',
        ));
        if ($this->hasRole('ROLE_USER')) {
            $divider = $search->addChild('divider', array(
                'label' => '',
            ));
            $divider->setAttributes(array(
                'role' => 'separator',
                'class' => 'divider',
            ));

            $search->addChild('Admin Reports', array(
                'uri' => '#',
            ));

            $search->addChild('Titles to Check', array(
                'route' => 'report_titles_check',
            ));
        }

        return $menu;
    }

    /**
     * Build the spotlight menu and return it.
     *
     * @param array $options
     *
     * @return ItemInterface
     */
    public function spotlightMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(array(
            'class' => 'nav navbar-nav',
        ));

        $spotlight = $menu->addChild('spotlight', array(
            'uri' => '#',
            'label' => 'Spotlight ' . self::CARET,
        ));
        $spotlight->setAttribute('dropdown', true);
        $spotlight->setLinkAttribute('class', 'dropdown-toggle');
        $spotlight->setLinkAttribute('data-toggle', 'dropdown');
        $spotlight->setChildrenAttribute('class', 'dropdown-menu');

        $repo = $this->em->getRepository(PostCategory::class);
        foreach ($this->spotlightMenuItems as $item) {
            $category = $repo->findOneBy(array(
                'name' => $item,
            ));
            if ( ! $category) {
                continue;
            }
            $spotlight->addChild($category->getLabel(), array(
                'route' => 'post_category_show',
                'routeParameters' => array(
                    'id' => $category->getId(),
                ),
            ));
        }

        return $menu;
    }

    /**
     * Build a user menu.
     *
     * @param array $options
     *
     * @return ItemInterface
     */
    public function userNavMenu(array $options) {
        $name = 'Login';
        if (isset($options['name'])) {
            $name = $options['name'];
        }
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(array(
            'class' => 'nav navbar-nav navbar-right',
        ));
        $menu->setAttribute('dropdown', true);
        $user = $this->getUser();
        if ( ! $this->hasRole('ROLE_USER')) {
            $menu->addChild($name, array(
                'route' => 'fos_user_security_login',
            ));

            return $menu;
        }

        $userMenu = $menu->addChild('user', array(
            'uri' => '#',
            'label' => $user->getUsername() . self::CARET,
        ));
        $userMenu->setAttribute('dropdown', true);
        $userMenu->setLinkAttribute('class', 'dropdown-toggle');
        $userMenu->setLinkAttribute('data-toggle', 'dropdown');
        $userMenu->setChildrenAttribute('class', 'dropdown-menu');
        $userMenu->addChild('Profile', array('route' => 'fos_user_profile_show'));
        $userMenu->addChild('Change password', array('route' => 'fos_user_change_password'));
        $userMenu->addChild('Logout', array('route' => 'fos_user_security_logout'));

        if ($this->hasRole('ROLE_ADMIN')) {
            $userMenu->addChild('divider', array(
                'label' => '',
            ));
            $userMenu['divider']->setAttributes(array(
                'role' => 'separator',
                'class' => 'divider',
            ));

            $userMenu->addChild('users', array(
                'label' => 'Users',
                'route' => 'user',
            ));
        }

        if ($this->hasRole('ROLE_COMMENT_ADMIN')) {
            $userMenu->addChild('comment_divider', array(
                'label' => '',
            ));
            $userMenu['comment_divider']->setAttributes(array(
                'role' => 'separator',
                'class' => 'divider',
            ));

            $userMenu->addChild('Comments', array(
                'route' => 'admin_comment_index',
            ));
            $userMenu->addChild('Comment Notes', array(
                'route' => 'admin_comment_note_index',
            ));
            $userMenu->addChild('Comment States', array(
                'route' => 'admin_comment_status_index',
            ));
        }

        return $menu;
    }

    /**
     * Build a menu for blog posts.
     *
     * @param array $options
     *
     * @return ItemInterface
     */
    public function postNavMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(array(
            'class' => 'nav navbar-nav',
        ));
        $menu->setAttribute('dropdown', true);

        $title = 'Announcements';
        if (isset($options['title'])) {
            $title = $options['title'];
        }

        $menu->addChild('announcements', array(
            'uri' => '#',
            'label' => $title . self::CARET,
        ));
        $menu['announcements']->setAttribute('dropdown', true);
        $menu['announcements']->setLinkAttribute('class', 'dropdown-toggle');
        $menu['announcements']->setLinkAttribute('data-toggle', 'dropdown');
        $menu['announcements']->setChildrenAttribute('class', 'dropdown-menu');

        $status = $this->em->getRepository('NinesBlogBundle:PostStatus')->findOneBy(array(
            'public' => true,
        ));
        $qb = $this->em->createQueryBuilder();
        $qb->select('p')
            ->from(Post::class, 'p')
            ->innerJoin(PostCategory::class, 'pc')
            ->where('pc.label NOT IN (:spotlightCategories)')
            ->orderBy('p.created', 'DESC')
            ->setMaxResults(10)
            ->setParameter(':spotlightCategories', $this->spotlightMenuItems)
        ;

        $posts = $qb->getQuery()->execute();
        foreach ($posts as $post) {
            if (in_array($post->getCategory()->getName(), $this->spotlightMenuItems)) {
                continue;
            }
            $menu['announcements']->addChild($post->getTitle(), array(
                'route' => 'post_show',
                'routeParameters' => array(
                    'id' => $post->getId(),
                ),
            ));
        }
        $menu['announcements']->addChild('divider', array(
            'label' => '',
        ));
        $menu['announcements']['divider']->setAttributes(array(
            'role' => 'separator',
            'class' => 'divider',
        ));

        $menu['announcements']->addChild('All Announcements', array(
            'route' => 'post_index',
        ));

        if ($this->hasRole('ROLE_BLOG_ADMIN')) {
            $menu['announcements']->addChild('divider', array(
                'label' => '',
            ));
            $menu['announcements']['divider']->setAttributes(array(
                'role' => 'separator',
                'class' => 'divider',
            ));

            $menu['announcements']->addChild('post_category', array(
                'label' => 'Post Categories',
                'route' => 'post_category_index',
            ));
            $menu['announcements']->addChild('post_status', array(
                'label' => 'Post Statuses',
                'route' => 'post_status_index',
            ));
        }

        return $menu;
    }
}
