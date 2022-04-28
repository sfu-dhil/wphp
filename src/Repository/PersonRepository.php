<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Nines\UserBundle\Entity\User;

/**
 * PersonRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PersonRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Person::class);
    }

    /**
     * Do a name search for a typeahead query.
     *
     * @param string $q
     *
     * @return mixed
     */
    public function typeaheadQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->where("CONCAT(COALESCE(e.lastName, ''), ' ', COALESCE(e.firstName, '')) LIKE :q");
        $qb->orWhere('e.id = :id');
        $qb->orderBy('e.lastName, e.firstName');
        $qb->setParameter('id', $q);
        $qb->setParameter('q', "%{$q}%");

        return $qb->getQuery()->execute();
    }

    /**
     * Find people by name and year.
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $dob
     * @param string $dod
     *
     * @return mixed
     */
    public function findByNameDates($firstName, $lastName, $dob, $dod) {
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere('e.lastName = :last');
        $qb->setParameter('last', $lastName);
        $qb->andWhere('e.firstName = :first');
        $qb->setParameter('first', $firstName);

        if ($dob) {
            $qb->andWhere('(e.dob = :dob) OR (YEAR(e.dob) = :dob)');
            $qb->setParameter('dob', $dob);
        }
        if ($dod) {
            $qb->andWhere('(e.dod = :dod) OR (YEAR(e.dod) = :dod)');
            $qb->setParameter('dod', $dod);
        }

        return $qb->getQuery()->execute();
    }

    /**
     * Build and return a complex search query from a search form.
     *
     * @param array $data
     * @param ?User $user
     *
     * @return Query
     */
    public function buildSearchQuery($data, $user) {
        $qb = $this->createQueryBuilder('e');
        $qb->orderBy('e.lastName');
        $qb->addOrderBy('e.firstName');
        $qb->addOrderBy('e.dob');
        if (isset($data['name']) && $data['name']) {
            $matches = [];
            if (preg_match('/^"(.*?)"$/', $data['name'], $matches)) {
                $qb->andWhere('CONCAT(e.firstName, \' \', e.lastName) LIKE :name');
                $qb->setParameter('name', $matches[1]);
            } else {
                $qb->andWhere('MATCH (e.lastName, e.firstName, e.title) AGAINST (:name BOOLEAN) > 0');
                $qb->setParameter('name', $data['name']);
            }
        }
        if (isset($data['order']) && $data['order']) {
            switch ($data['order']) {
                case 'lastname_asc':
                    $qb->orderBy('e.lastName', 'ASC');
                    $qb->addOrderBy('e.firstName', 'ASC');
                    $qb->addOrderBy('year(e.dob)');

                    break;

                case 'lastname_desc':
                    $qb->orderBy('e.lastName', 'DESC');
                    $qb->addOrderBy('e.firstName', 'ASC');
                    $qb->addOrderBy('year(e.dob)');

                    break;

                case 'firstname_asc':
                    $qb->orderBy('e.firstName', 'ASC');
                    $qb->addOrderBy('e.lastName', 'ASC');
                    $qb->addOrderBy('year(e.dob)');

                    break;

                case 'firstname_desc':
                    $qb->orderBy('e.firstName', 'DESC');
                    $qb->addOrderBy('e.lastName', 'ASC');
                    $qb->addOrderBy('year(e.dob)');

                    break;

                case 'gender_asc':
                    $qb->orderBy('e.gender', 'ASC');
                    $qb->addOrderBy('e.lastName', 'ASC');
                    $qb->addOrderBy('e.firstName', 'ASC');
                    $qb->addOrderBy('year(e.dob)');

                    break;

                case 'gender_desc':
                    $qb->orderBy('e.gender', 'DESC');
                    $qb->addOrderBy('e.lastName', 'ASC');
                    $qb->addOrderBy('e.firstName', 'ASC');
                    $qb->addOrderBy('year(e.dob)');

                    break;

                case 'birth_asc':
                    $qb->orderBy('year(e.dob)', 'ASC');
                    $qb->addOrderBy('e.lastName', 'ASC');
                    $qb->addOrderBy('e.firstName', 'ASC');

                    break;

                case 'birth_desc':
                    $qb->orderBy('year(e.dob)', 'DESC');
                    $qb->addOrderBy('e.lastName', 'ASC');
                    $qb->addOrderBy('e.firstName', 'ASC');

                    break;

                case 'death_asc':
                    $qb->orderBy('year(e.dod)', 'ASC');
                    $qb->addOrderBy('e.lastName', 'ASC');
                    $qb->addOrderBy('e.firstName', 'ASC');

                    break;

                case 'death_desc':
                    $qb->orderBy('year(e.dod)', 'DESC');
                    $qb->addOrderBy('e.lastName', 'ASC');
                    $qb->addOrderBy('e.firstName', 'ASC');

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

        if (isset($data['dob']) && $data['dob']) {
            $m = [];
            if (preg_match('/^\s*[0-9]{4}\s*$/', $data['dob'])) {
                $qb->andWhere('YEAR(e.dob) = :yearb');
                $qb->setParameter('yearb', $data['dob']);
            } elseif (preg_match('/^\s*(\*|[0-9]{4})\s*-\s*(\*|[0-9]{4})\s*$/', $data['dob'], $m)) {
                $from = ('*' === $m[1] ? -1 : $m[1]);
                $to = ('*' === $m[2] ? 9999 : $m[2]);
                $qb->andWhere(':fromb <= YEAR(e.dob) AND YEAR(e.dob) <= :tob');
                $qb->setParameter('fromb', $from);
                $qb->setParameter('tob', $to);
            }
        }

        if (isset($data['dod']) && $data['dod']) {
            $m = [];
            if (preg_match('/^\s*[0-9]{4}\s*$/', $data['dod'])) {
                $qb->andWhere('YEAR(e.dod) = :yeard');
                $qb->setParameter('yeard', $data['dod']);
            } elseif (preg_match('/^\s*(\*|[0-9]{4})\s*-\s*(\*|[0-9]{4})\s*$/', $data['dod'], $m)) {
                $from = ('*' === $m[1] ? -1 : $m[1]);
                $to = ('*' === $m[2] ? 9999 : $m[2]);
                $qb->andWhere(':fromd <= YEAR(e.dod) AND YEAR(e.dod) <= :tod');
                $qb->setParameter('fromd', $from);
                $qb->setParameter('tod', $to);
            }
        }

        if (isset($data['birthplace']) && $data['birthplace']) {
            $qb->innerJoin('e.cityOfBirth', 'b');
            $qb->andWhere('MATCH(b.alternatenames, b.name) AGAINST(:bpname BOOLEAN) > 0');
            $qb->setParameter('bpname', $data['birthplace']);
        }

        if (isset($data['deathplace']) && $data['deathplace']) {
            $qb->innerJoin('e.cityOfDeath', 'd');
            $qb->andWhere('MATCH(d.alternatenames, d.name) AGAINST(:dpname BOOLEAN) > 0');
            $qb->setParameter('dpname', $data['deathplace']);
        }
        if (isset($data['viafUrl']) && $data['viafUrl']) {
            if ('blank' === $data['viafUrl']) {
                $qb->andWhere('e.viafUrl IS NULL');
            } else {
                $qb->andWhere('MATCH(e.viafUrl) AGAINST(:viafUrl BOOLEAN) > 0');
                $qb->setParameter('viafUrl', $data['viafUrl']);
            }
        }
        if (isset($data['wikipediaUrl']) && $data['wikipediaUrl']) {
            if ('blank' === $data['wikipediaUrl']) {
                $qb->andWhere('e.wikipediaUrl IS NULL');
            } else {
                $qb->andWhere('MATCH(e.wikipediaUrl) AGAINST(:wikipediaUrl BOOLEAN) > 0');
                $qb->setParameter('wikipediaUrl', $data['wikipediaUrl']);
            }
        }
        if (isset($data['imageUrl']) && $data['imageUrl']) {
            if ('blank' === $data['imageUrl']) {
                $qb->andWhere('e.imageUrl IS NULL');
            } else {
                $qb->andWhere('MATCH(e.imageUrl) AGAINST(:imageUrl BOOLEAN) > 0');
                $qb->setParameter('imageUrl', $data['imageUrl']);
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

        if (isset($data['title_filter']) && count(array_filter($data['title_filter']))) {
            $filter = $data['title_filter'];
            $idx = '00';
            $trAlias = 'tr_' . $idx;
            $tAlias = 't_' . $idx;
            $qb->innerJoin('e.titleRoles', $trAlias)->innerJoin("{$trAlias}.title", $tAlias);

            if (isset($filter['id']) && $filter['id']) {
                $qb->andWhere("{$tAlias}.id = :{$tAlias}_id");
                $qb->setParameter("{$tAlias}_id", $filter['id']);
            }

            if (isset($filter['title']) && $filter['title']) {
                $qb->andWhere("MATCH({$tAlias}.title) AGAINST(:{$tAlias}_title BOOLEAN) > 0");
                $qb->setParameter("{$tAlias}_title", $filter['title']);
            }

            if (isset($filter['person_role']) && $filter['person_role']) {
                $qb->andWhere("{$trAlias}.role IN (:{$trAlias}_roles)");
                $qb->setParameter("{$trAlias}_roles", $filter['person_role']);
            }

            if (isset($filter['pubdate']) && $filter['pubdate']) {
                $m = [];
                if (preg_match('/^\s*[0-9]{4}\s*$/', $filter['pubdate'])) {
                    $qb->andWhere("YEAR(STRTODATE({$tAlias}.pubdate, '%Y')) = :{$tAlias}_year");
                    $qb->setParameter("{$tAlias}_year", $filter['pubdate']);
                } elseif (preg_match('/^\s*(\*|[0-9]{4})\s*-\s*(\*|[0-9]{4})\s*$/', $filter['pubdate'], $m)) {
                    $from = ('*' === $m[1] ? -1 : $m[1]);
                    $to = ('*' === $m[2] ? 9999 : $m[2]);
                    $qb->andWhere(":{$tAlias}_from <= YEAR(STRTODATE({$tAlias}.pubdate, '%Y')) AND YEAR(STRTODATE({$tAlias}.pubdate, '%Y')) <= :{$tAlias}_to");
                    $qb->setParameter("{$tAlias}_from", $from);
                    $qb->setParameter("{$tAlias}_to", $to);
                }
            }

            if (isset($filter['genre']) && count($filter['genre']) > 0) {
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

        if (isset($data['firm_filter']) && count(array_filter($data['firm_filter']))) {
            $filter = $data['firm_filter'];
            $idx = '01';
            $trAlias = 'tr_' . $idx;
            $tfrAlias = 'tfr_' . $idx;
            $tAlias = 't_' . $idx;
            $fAlias = 'f_' . $idx;

            $qb->innerJoin('e.titleRoles', $trAlias)
                ->innerJoin("{$trAlias}.title", $tAlias)
                ->innerJoin("{$tAlias}.titleFirmroles", $tfrAlias)
                ->innerJoin("{$tfrAlias}.firm", $fAlias)
            ;

            if (isset($filter['firm_id']) && $filter['firm_id']) {
                $qb->andWhere("{$fAlias}.id = :{$fAlias}_id");
                $qb->setParameter("{$fAlias}_id", $filter['firm_id']);
            }

            if (isset($filter['firm_name']) && $filter['firm_name']) {
                $qb->andWhere("MATCH({$fAlias}.name) AGAINST (:{$fAlias}_name BOOLEAN) > 0");
                $qb->setParameter("{$fAlias}_name", $filter['firm_name']);
            }

            if (isset($filter['firm_gender']) && $filter['firm_gender']) {
                $genders = [];
                if (in_array('M', $filter['firm_gender'], true)) {
                    $genders[] = 'M';
                }
                if (in_array('F', $filter['firm_gender'], true)) {
                    $genders[] = 'F';
                }
                if (in_array('U', $filter['firm_gender'], true)) {
                    $genders[] = 'U';
                }
                $qb->andWhere("{$fAlias}.gender in (:{$fAlias}_genders)");
                $qb->setParameter("{$fAlias}_genders", $genders);
            }

            if (isset($filter['firm_role']) && $filter['firm_role']) {
                $qb->andWhere("{$tfrAlias}.firmrole IN (:{$fAlias}_firmRoles)");
                $qb->setParameter("{$fAlias}_firmRoles", $filter['firm_role']);
            }

            if (isset($filter['firm_address']) && $filter['firm_address']) {
                $qb->andWhere("MATCH ({$fAlias}.streetAddress) AGAINST (:{$fAlias}_address BOOLEAN) > 0");
                $qb->setParameter("{$fAlias}_address", $filter['firm_address']);
            }
        }

        return $qb->getQuery()->execute();
    }

    /**
     * Find and return $limit random person entities.
     *
     * @param int $limit
     *
     * @return Collection|Person[]
     */
    public function random($limit) {
        $qb = $this->createQueryBuilder('e');
        $qb->orderBy('RAND()');
        $qb->setMaxResults($limit);

        return $qb->getQuery()->execute();
    }
}
