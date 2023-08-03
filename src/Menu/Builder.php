<?php

declare(strict_types=1);

namespace App\Menu;

use App\Entity\Post;
use App\Entity\PostCategory;
use App\Entity\PostStatus;
use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Knp\Menu\ItemInterface;
use Nines\UtilBundle\Menu\AbstractBuilder;

/**
 * Menu builder for the navigation and search menus.
 */
class Builder extends AbstractBuilder {
    public function __construct(
        private PageRepository $pageRepository,
        private EntityManagerInterface $em,
        private array $spotlightMenuItems = [],
    ) {
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
            'attributes' => [
                'class' => 'nav-item dropdown',
            ],
            'linkAttributes' => [
                'class' => 'nav-link', // dropdown-toggle
                'role' => 'button',
                'data-bs-toggle' => 'dropdown',
                'id' => 'browse-dropdown',
            ],
            'childrenAttributes' => [
                'class' => 'dropdown-menu text-small shadow dropdown-menu-end',
                'aria-labelledby' => 'browse-dropdown',
            ],
        ]);

        $browse->addChild('Search Titles', [
            'route' => 'title_index',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);
        $browse->addChild('Search Persons', [
            'route' => 'person_index',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);
        $browse->addChild('Search Firms', [
            'route' => 'firm_index',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);

        $browse->addChild('Formats', [
            'route' => 'format_index',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);
        $browse->addChild('Genres', [
            'route' => 'genre_index',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);
        $browse->addChild('Sources', [
            'route' => 'source_index',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);
        $browse->addChild('Contributor Roles', [
            'route' => 'role_index',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);
        $browse->addChild('Firm Roles', [
            'route' => 'firmrole_index',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);

        if ($this->hasRole('ROLE_USER')) {
            $browse->addChild('divider1', [
                'label' => '',
                'attributes' => [
                    'role' => 'separator',
                    'class' => 'divider',
                ],
            ]);
            $browse->addChild('AAS', [
                'route' => 'resource_aas_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('Currencies', [
                'route' => 'currency_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('Geonames', [
                'route' => 'geonames_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('English Novel', [
                'route' => 'resource_en_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('ESTC', [
                'route' => 'resource_estc_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('Jackson', [
                'route' => 'resource_jackson_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('Orlando', [
                'route' => 'resource_orlando_biblio_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('Osborne', [
                'route' => 'resource_osborne_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('divider2', [
                'label' => '',
                'attributes' => [
                    'role' => 'separator',
                    'class' => 'divider',
                ],
            ]);
            $browse->addChild('All Reports', [
                'route' => 'report_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);

            $browse->addChild('AAS Titles', [
                'route' => 'unchecked_aas_titles',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('Titles to Final Check', [
                'route' => 'report_titles_check',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('Titles with Bad Publication Date', [
                'route' => 'report_titles_date',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('Firms to Check', [
                'route' => 'report_firms_fc',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('Persons to Check', [
                'route' => 'report_persons_fc',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('Editions to Check', [
                'route' => 'report_editions_to_check',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('Titles with Unverified People', [
                'route' => 'titles_unverified_persons',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('Titles with Unverified Firms', [
                'route' => 'titles_unverified_firms',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
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
            'attributes' => [
                'class' => 'nav-item dropdown',
            ],
            'linkAttributes' => [
                'class' => 'nav-link', // dropdown-toggle
                'role' => 'button',
                'data-bs-toggle' => 'dropdown',
                'id' => 'spotlight-dropdown',
            ],
            'childrenAttributes' => [
                'class' => 'dropdown-menu text-small shadow dropdown-menu-end',
                'aria-labelledby' => 'spotlight-dropdown',
            ],
        ]);

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
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
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
            'attributes' => [
                'class' => 'nav-item dropdown',
            ],
            'linkAttributes' => [
                'class' => 'nav-link', // dropdown-toggle
                'role' => 'button',
                'data-bs-toggle' => 'dropdown',
                'id' => 'research-dropdown',
            ],
            'childrenAttributes' => [
                'class' => 'dropdown-menu text-small shadow dropdown-menu-end',
                'aria-labelledby' => 'research-dropdown',
            ],
        ]);
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
            ->orderBy('p.created', 'DESC')->setMaxResults(10)
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
            $research->addChild('r_' . $post->getId(), [
                'label' => $post->getTitle(),
                'route' => 'nines_blog_post_show',
                'routeParameters' => [
                    'id' => $post->getId(),
                ],
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
        }
        $research->addChild('divider1', [
            'label' => '',
            'attributes' => [
                'role' => 'separator',
                'class' => 'divider',
            ],
        ]);
        $research->addChild('All Research', [
            'route' => 'nines_blog_post_category_show',
            'routeParameters' => [
                'id' => $category->getId(),
            ],
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
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

        $user = $this->getUser();
        if ( ! $this->hasRole('ROLE_USER')) {
            $menu->addChild($name, [
                'route' => 'nines_user_security_login',
            ]);

            return $menu;
        }

        $userMenu = $menu->addChild('user', [
            'uri' => '#',
            'label' => $user->getUserIdentifier(),
            'attributes' => [
                'class' => 'nav-item dropdown',
            ],
            'linkAttributes' => [
                'class' => 'nav-link', // dropdown-toggle
                'role' => 'button',
                'data-bs-toggle' => 'dropdown',
                'id' => 'user-dropdown',
            ],
            'childrenAttributes' => [
                'class' => 'dropdown-menu text-small shadow dropdown-menu-end',
                'aria-labelledby' => 'user-dropdown',
            ],
        ]);

        $userMenu->addChild('Profile', [
            'route' => 'nines_user_profile_index',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);
        $userMenu->addChild('Change password', [
            'route' => 'nines_user_profile_password',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);
        $userMenu->addChild('Logout', [
            'route' => 'nines_user_security_logout',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);

        if ($this->hasRole('ROLE_ADMIN')) {
            $userMenu->addChild('divider1', [
                'label' => '',
                'attributes' => [
                    'role' => 'separator',
                    'class' => 'divider',
                ],
            ]);
            $userMenu->addChild('users', [
                'label' => 'Users',
                'route' => 'nines_user_admin_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
        }

        if ($this->hasRole('ROLE_FEEDBACK_ADMIN')) {
            $userMenu->addChild('divider2', [
                'label' => '',
                'attributes' => [
                    'role' => 'separator',
                    'class' => 'divider',
                ],
            ]);
            $userMenu->addChild('Comments', [
                'route' => 'nines_feedback_comment_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $userMenu->addChild('Comment Notes', [
                'route' => 'nines_feedback_comment_note_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $userMenu->addChild('Comment States', [
                'route' => 'nines_feedback_comment_status_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
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

        $title = 'Announcements';
        if (isset($options['title'])) {
            $title = $options['title'];
        }

        $menu->addChild('announcements', [
            'uri' => '#',
            'label' => $title,
            'attributes' => [
                'class' => 'nav-item dropdown',
            ],
            'linkAttributes' => [
                'class' => 'nav-link', // dropdown-toggle
                'role' => 'button',
                'data-bs-toggle' => 'dropdown',
                'id' => 'announcements-dropdown',
            ],
            'childrenAttributes' => [
                'class' => 'dropdown-menu text-small shadow dropdown-menu-end',
                'aria-labelledby' => 'announcements-dropdown',
            ],
        ]);

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
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
        }

        if ($this->hasRole('ROLE_BLOG_ADMIN')) {
            $menu['announcements']->addChild('divider1', [
                'label' => '',
                'attributes' => [
                    'role' => 'separator',
                    'class' => 'divider',
                ],
            ]);
            $menu['announcements']->addChild('All Announcements', [
                'route' => 'nines_blog_post_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);

            $menu['announcements']->addChild('post_category', [
                'label' => 'Post Categories',
                'route' => 'nines_blog_post_category_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $menu['announcements']->addChild('post_status', [
                'label' => 'Post Statuses',
                'route' => 'nines_blog_post_status_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
        }

        return $menu;
    }

    /**
     * @param array<string,string> $options
     */
    public function pageMenu(array $options) : ItemInterface {
        $menu = $this->dropdown($options['title'] ?? 'About');
        $menu->setLinkAttribute('class', 'nav-link');

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
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
        }

        if ($this->hasRole('ROLE_BLOG_ADMIN')) {
            $menu->addChild('divider', [
                'label' => '',
                'attributes' => [
                    'role' => 'separator',
                    'class' => 'divider',
                ],
            ]);
            $menu->addChild('All Pages', [
                'route' => 'nines_blog_page_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
        }

        return $menu->getParent();
    }
}
