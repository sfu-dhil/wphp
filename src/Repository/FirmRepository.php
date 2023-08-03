<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Firm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Nines\UserBundle\Entity\User;

/**
 * FirmRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 * @phpstan-extends ServiceEntityRepository<Firm>
 */
class FirmRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Firm::class);
    }

    /**
     * Do a name search for a typeahead widget.
     *
     * @param string $q
     *
     * @return mixed
     */
    public function typeaheadQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->where('e.name LIKE :q');
        $qb->orWhere('e.id = :id');
        $qb->orderBy('e.name');
        $qb->setParameter('id', $q);
        $qb->setParameter('q', "%{$q}%");

        return $qb->getQuery()->execute();
    }

    /**
     * Build a full text, complex search query and return it. Takes all the
     * parameters from the firm search and does smart things with them.
     *
     * @param array $data The search form's data from $form->getData().
     * @param ?User $user
     *
     * @return Query
     */
    public function buildSearchQuery($data, $user) {
        $qb = $this->createQueryBuilder('e');
        $qb->orderBy('e.name');
        $qb->addOrderBy('e.startDate');
        if (isset($data['name']) && $data['name']) {
            $qb->add('where', 'MATCH (e.name) AGAINST(:name BOOLEAN) > 0');
            $qb->setParameter('name', $data['name']);
        }
        if (isset($data['order']) && $data['order']) {
            switch ($data['order']) {
                case 'name_asc':
                    $qb->orderBy('e.name', 'ASC');
                    $qb->addOrderBy('e.startDate');

                    break;

                case 'name_desc':
                    $qb->orderBy('e.name', 'DESC');
                    $qb->addOrderBy('e.startDate');

                    break;

                case 'city_asc':
                    $qb->innerJoin('e.city', 'c');
                    $qb->orderBy('c.name', 'ASC');
                    $qb->addOrderBy('e.name', 'ASC');
                    $qb->addOrderBy('e.startDate');

                    break;

                case 'city_desc':
                    $qb->innerJoin('e.city', 'c');
                    $qb->orderBy('c.name', 'DESC');
                    $qb->addOrderBy('e.name', 'ASC');
                    $qb->addOrderBy('e.startDate');

                    break;

                case 'start_asc':
                    $qb->orderBy('e.startDate');
                    $qb->addOrderBy('e.name', 'ASC');

                    break;

                case 'start_desc':
                    $qb->orderBy('e.startDate', 'DESC');
                    $qb->addOrderBy('e.name', 'ASC');

                    break;

                case 'end_asc':
                    $qb->orderBy('e.endDate', 'ASC');
                    $qb->addOrderBy('e.name', 'ASC');

                    break;

                case 'end_desc':
                    $qb->orderBy('e.endDate', 'DESC');
                    $qb->addOrderBy('e.name', 'ASC');

                    break;
            }
        }
        if (isset($data['id']) && $data['id']) {
            $qb->andWhere('e.id = :id');
            $qb->setParameter('id', $data['id']);
        }
        if (isset($data['gender']) && $data['gender']) {
            $genders = [];
            if (in_array('M', $data['gender'], true)) {
                $genders[] = 'M';
            }
            if (in_array('F', $data['gender'], true)) {
                $genders[] = 'F';
            }
            if (in_array('U', $data['gender'], true)) {
                $genders[] = 'U';
            }
            $qb->andWhere('e.gender in (:genders)');
            $qb->setParameter('genders', $genders);
        }
        if (isset($data['address']) && $data['address']) {
            $qb->andWhere('MATCH (e.streetAddress) AGAINST(:address BOOLEAN) > 0');
            $qb->setParameter('address', $data['address']);
        }
        if (isset($data['city']) && $data['city']) {
            if ( ! $data['order'] || ('city_asc' !== $data['order'] && 'city_desc' !== $data['order'])) {
                $qb->innerJoin('e.city', 'c');
            }
            $qb->andWhere('MATCH(c.alternatenames, c.name) AGAINST(:cname BOOLEAN) > 0');
            $qb->setParameter('cname', $data['city']);
        }
        if (isset($data['start']) && $data['start']) {
            $m = [];
            if (preg_match('/^\s*[0-9]{4}\s*$/', (string) $data['start'])) {
                $qb->andWhere('e.startDate = :yearb');
                $qb->setParameter('yearb', $data['start']);
            } elseif (preg_match('/^\s*(\*|[0-9]{4})\s*-\s*(\*|[0-9]{4})\s*$/', (string) $data['start'], $m)) {
                $from = ('*' === $m[1] ? -1 : $m[1]);
                $to = ('*' === $m[2] ? 9999 : $m[2]);
                $qb->andWhere(':fromb <= e.startDate AND e.startDate <= :tob');
                $qb->setParameter('fromb', $from);
                $qb->setParameter('tob', $to);
            }
        }

        if (isset($data['end']) && $data['end']) {
            $m = [];
            if (preg_match('/^\s*[0-9]{4}\s*$/', (string) $data['end'])) {
                $qb->andWhere('e.endDate = :yeare');
                $qb->setParameter('yeare', $data['end']);
            } elseif (preg_match('/^\s*(\*|[0-9]{4})\s*-\s*(\*|[0-9]{4})\s*$/', (string) $data['end'], $m)) {
                $from = ('*' === $m[1] ? -1 : $m[1]);
                $to = ('*' === $m[2] ? 9999 : $m[2]);
                $qb->andWhere(':frome <= e.endDate AND e.endDate <= :toe');
                $qb->setParameter('frome', $from);
                $qb->setParameter('toe', $to);
            }
        }

        if ($user) {
            if (isset($data['finalcheck'])) {
                $qb->andWhere('e.finalcheck = :finalcheck');
                $qb->setParameter('finalcheck', 'Y' === $data['finalcheck']);
            }
        } else {
            $qb->andWhere('e.finalcheck = 1');
        }

        if (isset($data['title_filter']) && count((array) array_filter($data['title_filter']))) {
            $filter = $data['title_filter'];
            $idx = '00';
            $tfrAlias = 'tfr_' . $idx;
            $tAlias = 't_' . $idx;
            $qb->innerJoin('e.titleFirmroles', $tfrAlias)
                ->innerJoin("{$tfrAlias}.title", $tAlias)
            ;

            if (isset($filter['id']) && $filter['id']) {
                $qb->andWhere("{$tAlias}.id = :{$tAlias}_id");
                $qb->setParameter("{$tAlias}_id", $filter['id']);
            }

            if (isset($filter['title']) && $filter['title']) {
                $qb->andWhere("MATCH({$tAlias}.title) AGAINST(:{$tAlias}_title BOOLEAN) > 0");
                $qb->setParameter("{$tAlias}_title", $filter['title']);
            }

            if (isset($filter['pubdate']) && $filter['pubdate']) {
                $m = [];
                if (preg_match('/^\s*[0-9]{4}\s*$/', (string) $filter['pubdate'])) {
                    $qb->andWhere("YEAR(STRTODATE({$tAlias}.pubdate, '%Y')) = :{$tAlias}_year");
                    $qb->setParameter("{$tAlias}_year", $filter['pubdate']);
                } elseif (preg_match('/^\s*(\*|[0-9]{4})\s*-\s*(\*|[0-9]{4})\s*$/', (string) $filter['pubdate'], $m)) {
                    $from = ('*' === $m[1] ? -1 : $m[1]);
                    $to = ('*' === $m[2] ? 9999 : $m[2]);
                    $qb->andWhere(":{$tAlias}_from <= YEAR(STRTODATE({$tAlias}.pubdate, '%Y')) AND YEAR(STRTODATE({$tAlias}.pubdate, '%Y')) <= :{$tAlias}_to");
                    $qb->setParameter("{$tAlias}_from", $from);
                    $qb->setParameter("{$tAlias}_to", $to);
                }
            }

            if (isset($filter['genre']) && (is_countable($filter['genre']) ? count($filter['genre']) : 0) > 0) {
                $qb->andWhere("{$tAlias}.genre in (:{$tAlias}_genres)");
                $qb->setParameter("{$tAlias}_genres", $filter['genre']);
            }

            if (isset($filter['location']) && $filter['location']) {
                $gAlias = 'g_' . $idx;
                $qb->innerJoin("{$tAlias}.locationOfPrinting", $gAlias);
                $qb->andWhere("MATCH({$gAlias}.alternatenames, {$gAlias}.name) AGAINST(:{$gAlias}_location BOOLEAN) > 0");
                $qb->setParameter("{$gAlias}_location", $filter['location']);
            }
        }

        if (isset($data['person_filter']) && count((array) array_filter($data['person_filter']))) {
            $filter = $data['person_filter'];
            $idx = '01';
            $tfrAlias = 'tfr_' . $idx;
            $tAlias = 't_' . $idx;
            $trAlias = 'tr_' . $idx;
            $pAlias = 'p_' . $idx;

            $qb->innerJoin('e.titleFirmroles', $tfrAlias)
                ->innerJoin("{$tfrAlias}.title", $tAlias)
                ->innerJoin("{$tAlias}.titleRoles", $trAlias)
                ->innerJoin("{$trAlias}.person", $pAlias)
            ;

            if (isset($filter['id']) && $filter['id']) {
                $qb->andWhere("{$pAlias}.id = :{$pAlias}_id");
                $qb->setParameter("{$pAlias}_id", $filter['id']);
            }
            if (isset($filter['name']) && $filter['name']) {
                $qb->andWhere("MATCH ({$pAlias}.lastName, {$pAlias}.firstName, {$pAlias}.title) AGAINST(:{$pAlias}_name BOOLEAN) > 0");
                $qb->setParameter("{$pAlias}_name", $filter['name']);
            }
            if (isset($filter['gender']) && $filter['gender']) {
                $genders = [];
                if (in_array('M', $filter['gender'], true)) {
                    $genders[] = 'M';
                }
                if (in_array('F', $filter['gender'], true)) {
                    $genders[] = 'F';
                }
                if (in_array('U', $filter['gender'], true)) {
                    $genders[] = '';
                }
                $qb->andWhere("{$pAlias}.gender in (:genders)");
                $qb->setParameter('genders', $genders);
            }

            if (isset($filter['person_role']) && (is_countable($filter['person_role']) ? count($filter['person_role']) : 0) > 0) {
                $qb->andWhere("{$trAlias}.role in (:roles_{$idx})");
                $qb->setParameter("roles_{$idx}", $filter['person_role']);
            }
        }

        return $qb->getQuery();
    }
}
