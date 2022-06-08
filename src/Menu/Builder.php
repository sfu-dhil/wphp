<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Menu;

use App\Entity\Post;
use App\Entity\PostCategory;
use App\Entity\PostStatus;
use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Knp\Menu\ItemInterface;
use Nines\UserBundle\Entity\User;
use Nines\UtilBundle\Menu\AbstractBuilder;

/**
 * Menu builder for the navigation and search menus.
 */
class Builder extends AbstractBuilder {
    /**
     * List of spotlight menu items.
     *
     * @var array<string>
     */
    private $spotlightMenuItems;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    private ?PageRepository $pageRepository;

    /**
     * Build the menu builder.
     *
     * @param array<string> $spotlightMenuItems
     */
    public function __construct($spotlightMenuItems, EntityManagerInterface $em) {
        $this->spotlightMenuItems = $spotlightMenuItems;
        $this->em = $em;
    }

    /**
     * Build the navigation menu and return it.
     *
     * @param array<string,mixed> $options
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
            'label' => 'Database',
        ]);
        $browse->setAttribute('dropdown', true);
        $browse->setLinkAttribute('class', 'dropdown-toggle');
        $browse->setLinkAttribute('data-toggle', 'dropdown');
        $browse->setChildrenAttribute('class', 'dropdown-menu');

        $browse->addChild('Search Titles', [
            'route' => 'title_index',
        ]);
        $browse->addChild('Search Persons', [
            'route' => 'person_index',
        ]);
        $browse->addChild('Search Firms', [
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
            $browse->addChild('AAS', [
                'route' => 'resource_aas_index',
            ]);
            $browse->addChild('Currencies', [
                'route' => 'currency_index',
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
            $divider = $browse->addChild('divider2', [
                'label' => '',
            ]);
            $divider->setAttributes([
                'role' => 'separator',
                'class' => 'divider',
            ]);
            $browse->addChild('Titles to Final Check', [
                'route' => 'report_titles_check',
            ]);

            $browse->addChild('Titles with Bad Publication Date', [
                'route' => 'report_titles_date',
            ]);
            $browse->addChild('Firms to Check', [
                'route' => 'report_firms_fc',
            ]);
            $browse->addChild('Persons to Check', [
                'route' => 'report_persons_fc',
            ]);
            $browse->addChild('Editions to Check', [
                'route' => 'report_editions_to_check',
            ]);
            $browse->addChild('Titles with Unverified People', [
                'route' => 'titles_unverified_persons',
            ]);
            $browse->addChild('Titles with Unverified Firms', [
                'route' => 'titles_unverified_firms',
            ]);
        }

        return $menu;
    }

    /**
     * Build the spotlight menu and return it.
     *
     * @param array<string,mixed> $options
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
            'label' => 'Spotlights',
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
     * Build the research menu and return it.
     *
     * @param array<string,mixed> $options
     *
     * @return ItemInterface
     */
    public function researchMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav',
        ]);

        $research = $menu->addChild('research', [
            'uri' => '#',
            'label' => 'Research',
        ]);
        $research->setAttribute('dropdown', true);
        $research->setLinkAttribute('class', 'dropdown-toggle');
        $research->setLinkAttribute('data-toggle', 'dropdown');
        $research->setChildrenAttribute('class', 'dropdown-menu');

        $category = $this->em->getRepository(PostCategory::class)
            ->findOneBy(['name' => 'research'])
        ;

        if ( ! $category) {
            return $research;
        }

        $qb = $this->em->createQueryBuilder();
        $qb->select('p')->from(Post::class, 'p')
            ->where('p.category = :category')
            ->setParameter('category', $category)
            ->orderBy('p.created', 'DESC')->setMaxResults(10);

        if ( ! $this->hasRole('ROLE_USER')) {
            $status = $this->em->getRepository(PostStatus::class)->findOneBy([
                'public' => true,
            ]);
            $qb->andWhere('p.status = :status');
            $qb->setParameter('status', $status);
        }

        $posts = $qb->getQuery()->execute();

        foreach ($posts as $post) {
            $research->addChild('r_' . $post->getId(), [
                'label' => $post->getTitle(),
                'route' => 'nines_blog_post_show',
                'routeParameters' => [
                    'id' => $post->getId(),
                ],
            ]);
        }

        $research->addChild('divider');
        $research['divider']->setAttributes([
            'role' => 'separator',
            'class' => 'divider',
        ]);
        $research->addChild('All Research', [
            'route' => 'nines_blog_post_category_show',
            'routeParameters' => [
                'id' => $category->getId(),
            ],
        ]);

        return $menu;
    }

    /**
     * Build a user menu.
     *
     * @param array<string,mixed> $options
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

        if ($this->hasRole('ROLE_FEEDBACK_ADMIN')) {
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
     * @param array<string,mixed> $options
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

        $status = $this->em->getRepository(PostStatus::class)->findOneBy([
            'public' => true,
        ]);
        $qb = $this->em->createQueryBuilder();
        $qb->select('p')
            ->from(Post::class, 'p')
            ->innerJoin(PostCategory::class, 'pc', Join::WITH, 'p.category = pc')
            ->where('pc.name = \'news\'')
            ->orderBy('p.created', 'DESC')
            ->setMaxResults(10)
        ;
        if ( ! $this->hasRole('ROLE_USER')) {
            $status = $this->em->getRepository(PostStatus::class)->findOneBy([
                'public' => true,
            ]);
            $qb->andWhere('p.status = :status');
            $qb->setParameter('status', $status);
        }

        $posts = $qb->getQuery()->execute();

        foreach ($posts as $post) {
            $menu['announcements']->addChild('p_' . $post->getId(), [
                'label' => $post->getTitle(),
                'route' => 'nines_blog_post_show',
                'routeParameters' => [
                    'id' => $post->getId(),
                ],
            ]);
        }

        if ($this->hasRole('ROLE_BLOG_ADMIN')) {
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

    /**
     * @param array<string,string> $options
     */
    public function pageMenu(array $options) : ItemInterface {
        $menu = $this->dropdown($options['title'] ?? 'About');

        // @TODO turn this into menuQuery().
        $pages = $this->pageRepository->findBy(
            ['public' => true, 'homepage' => false, 'inMenu' => true],
            ['weight' => 'ASC', 'title' => 'ASC'],
        );
        foreach ($pages as $page) {
            $menu->addChild($page->getTitle(), [
                'route' => 'nines_blog_page_show',
                'routeParameters' => [
                    'id' => $page->getId(),
                ],
            ]);
        }

        if ($this->hasRole('ROLE_BLOG_ADMIN')) {
            $this->addDivider($menu);
            $menu->addChild('All Pages', [
                'route' => 'nines_blog_page_index',
            ]);
        }

        return $menu->getParent();
    }

    /**
     * @required
     *
     * @codeCoverageIgnore
     */
    public function setPageRepository(PageRepository $pageRepository) : void {
        $this->pageRepository = $pageRepository;
    }
}
