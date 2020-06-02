<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Menu;

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
     * @return ItemInterface
     */
    public function mainMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav',
        ]);

        $browse = $menu->addChild('browse', [
            'uri' => '#',
            'label' => 'Explore',
        ]);
        $browse->setAttribute('dropdown', true);
        $browse->setLinkAttribute('class', 'dropdown-toggle');
        $browse->setLinkAttribute('data-toggle', 'dropdown');
        $browse->setChildrenAttribute('class', 'dropdown-menu');

        $browse->addChild('Titles', [
            'route' => 'title_index',
        ]);
        $browse->addChild('Persons', [
            'route' => 'person_index',
        ]);
        $browse->addChild('Firms', [
            'route' => 'firm_index',
        ]);

        $browse->addChild('Formats', [
            'route' => 'format_index',
        ]);
        $browse->addChild('Genres', [
            'route' => 'genre_index',
        ]);
        $browse->addChild('Sources', [
            'route' => 'source_index',
        ]);
        $browse->addChild('Contributor Roles', [
            'route' => 'role_index',
        ]);
        $browse->addChild('Firm Roles', [
            'route' => 'firmrole_index',
        ]);

        if ($this->hasRole('ROLE_USER')) {
            $divider = $browse->addChild('divider', [
                'label' => '',
            ]);
            $divider->setAttributes([
                'role' => 'separator',
                'class' => 'divider',
            ]);
            $browse->addChild('Geonames', [
                'route' => 'geonames_index',
            ]);
            $browse->addChild('English Novel', [
                'route' => 'resource_en_index',
            ]);
            $browse->addChild('ESTC', [
                'route' => 'resource_estc_index',
            ]);
            $browse->addChild('Jackson', [
                'route' => 'resource_jackson_index',
            ]);
            $browse->addChild('Orlando', [
                'route' => 'resource_orlando_biblio_index',
            ]);
            $browse->addChild('Osborne', [
                'route' => 'resource_osborne_index',
            ]);
        }

        return $menu;
    }

    /**
     * Build the search menu and return it.
     *
     * @return ItemInterface
     */
    public function searchMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav',
        ]);

        $search = $menu->addChild('search', [
            'uri' => '#',
            'label' => 'Search',
        ]);
        $search->setAttribute('dropdown', true);
        $search->setLinkAttribute('class', 'dropdown-toggle');
        $search->setLinkAttribute('data-toggle', 'dropdown');
        $search->setChildrenAttribute('class', 'dropdown-menu');

        $search->addChild('Titles', [
            'route' => 'title_search',
        ]);
        $search->addChild('Persons', [
            'route' => 'person_search',
        ]);
        $search->addChild('Firms', [
            'route' => 'firm_search',
        ]);
        if ($this->hasRole('ROLE_USER')) {
            $divider = $search->addChild('divider', [
                'label' => '',
            ]);
            $divider->setAttributes([
                'role' => 'separator',
                'class' => 'divider',
            ]);

            $search->addChild('Titles to Final Check', [
                'route' => 'report_titles_check',
            ]);

            $search->addChild('Titles with Bad Publication Date', [
                'route' => 'report_titles_date',
            ]);

            $search->addChild('People with Bad Wikipedia URLs', [
                'route' => 'report_person_wiki',
            ]);
        }

        return $menu;
    }

    /**
     * Build the spotlight menu and return it.
     *
     * @return ItemInterface
     */
    public function spotlightMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav',
        ]);

        $spotlight = $menu->addChild('spotlight', [
            'uri' => '#',
            'label' => 'Spotlight',
        ]);
        $spotlight->setAttribute('dropdown', true);
        $spotlight->setLinkAttribute('class', 'dropdown-toggle');
        $spotlight->setLinkAttribute('data-toggle', 'dropdown');
        $spotlight->setChildrenAttribute('class', 'dropdown-menu');

        $repo = $this->em->getRepository(PostCategory::class);
        foreach ($this->spotlightMenuItems as $item) {
            $category = $repo->findOneBy([
                'name' => $item,
            ]);
            if ( ! $category) {
                continue;
            }
            $spotlight->addChild($category->getLabel(), [
                'route' => 'nines_blog_post_category_show',
                'routeParameters' => [
                    'id' => $category->getId(),
                ],
            ]);
        }

        return $menu;
    }

    /**
     * Build a user menu.
     *
     * @return ItemInterface
     */
    public function userNavMenu(array $options) {
        $name = 'Login';
        if (isset($options['name'])) {
            $name = $options['name'];
        }
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav navbar-right',
        ]);
        $menu->setAttribute('dropdown', true);
        $user = $this->getUser();
        if ( ! $this->hasRole('ROLE_USER')) {
            $menu->addChild($name, [
                'route' => 'nines_user_security_login',
            ]);

            return $menu;
        }

        $userMenu = $menu->addChild('user', [
            'uri' => '#',
            'label' => $user->getUsername(),
        ]);
        $userMenu->setAttribute('dropdown', true);
        $userMenu->setLinkAttribute('class', 'dropdown-toggle');
        $userMenu->setLinkAttribute('data-toggle', 'dropdown');
        $userMenu->setChildrenAttribute('class', 'dropdown-menu');

        $userMenu->addChild('Profile', ['route' => 'nines_user_profile_index']);
        $userMenu->addChild('Change password', ['route' => 'nines_user_profile_password']);
        $userMenu->addChild('Logout', ['route' => 'nines_user_security_logout']);

        if ($this->hasRole('ROLE_ADMIN')) {
            $userMenu->addChild('divider', [
                'label' => '',
            ]);
            $userMenu['divider']->setAttributes([
                'role' => 'separator',
                'class' => 'divider',
            ]);

            $userMenu->addChild('users', [
                'label' => 'Users',
                'route' => 'nines_user_admin_index',
            ]);
        }

        if ($this->hasRole('ROLE_COMMENT_ADMIN')) {
            $userMenu->addChild('comment_divider', [
                'label' => '',
            ]);
            $userMenu['comment_divider']->setAttributes([
                'role' => 'separator',
                'class' => 'divider',
            ]);

            $userMenu->addChild('Comments', [
                'route' => 'nines_feedback_comment_index',
            ]);
            $userMenu->addChild('Comment Notes', [
                'route' => 'nines_feedback_comment_note_index',
            ]);
            $userMenu->addChild('Comment States', [
                'route' => 'nines_feedback_comment_status_index',
            ]);
        }

        return $menu;
    }

    /**
     * Build a menu for blog posts.
     *
     * @return ItemInterface
     */
    public function postNavMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav',
        ]);
        $menu->setAttribute('dropdown', true);

        $title = 'Announcements';
        if (isset($options['title'])) {
            $title = $options['title'];
        }

        $menu->addChild('announcements', [
            'uri' => '#',
            'label' => $title,
        ]);
        $menu['announcements']->setAttribute('dropdown', true);
        $menu['announcements']->setLinkAttribute('class', 'dropdown-toggle');
        $menu['announcements']->setLinkAttribute('data-toggle', 'dropdown');
        $menu['announcements']->setChildrenAttribute('class', 'dropdown-menu');

        $status = $this->em->getRepository('NinesBlogBundle:PostStatus')->findOneBy([
            'public' => true,
        ]);
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
            if (in_array($post->getCategory()->getName(), $this->spotlightMenuItems, true)) {
                continue;
            }
            $menu['announcements']->addChild($post->getTitle(), [
                'route' => 'nines_blog_post_show',
                'routeParameters' => [
                    'id' => $post->getId(),
                ],
            ]);
        }
        $menu['announcements']->addChild('divider', [
            'label' => '',
        ]);
        $menu['announcements']['divider']->setAttributes([
            'role' => 'separator',
            'class' => 'divider',
        ]);

        $menu['announcements']->addChild('All Announcements', [
            'route' => 'nines_blog_post_index',
        ]);

        if ($this->hasRole('ROLE_BLOG_ADMIN')) {
            $menu['announcements']->addChild('divider', [
                'label' => '',
            ]);
            $menu['announcements']['divider']->setAttributes([
                'role' => 'separator',
                'class' => 'divider',
            ]);

            $menu['announcements']->addChild('post_category', [
                'label' => 'Post Categories',
                'route' => 'nines_blog_post_category_index',
            ]);
            $menu['announcements']->addChild('post_status', [
                'label' => 'Post Statuses',
                'route' => 'nines_blog_post_status_index',
            ]);
        }

        return $menu;
    }
}
