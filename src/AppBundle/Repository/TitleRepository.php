<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Title;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * Title Repository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TitleRepository extends EntityRepository
{

    /**
     * Return the next title by ID.
     *
     * @param Title $title
     * @return Title|Null
     */
    public function next(Title $title) {
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere('e.id > :id');
        $qb->setParameter('id', $title->getId());
        $qb->addOrderBy('e.id', 'ASC');
        $qb->setMaxResults(1);
        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * Return the next title by ID.
     *
     * @param Title $title
     * @return Title|Null
     */
    public function previous(Title $title) {
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere('e.id < :id');
        $qb->setParameter('id', $title->getId());
        $qb->addOrderBy('e.id', 'DESC');
        $qb->setMaxResults(1);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function search($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->addSelect("MATCH_AGAINST (e.title, :q 'IN BOOLEAN MODE') as score");
        $qb->add('where', "MATCH_AGAINST (e.title, :q 'IN BOOLEAN MODE') > 0.5");
        $qb->orderBy('score', 'desc');
        $qb->setParameter('q', $q);
        return $qb->getQuery();
    }

    /**
     * @param array $data
     * @return Query
     */
    public function buildSearchQuery($data = array()) {
        $qb = $this->createQueryBuilder('e');
        $qb->distinct();
        if (isset($data['title']) && $data['title']) {
            $qb->andWhere("MATCH_AGAINST (e.title, :title 'IN BOOLEAN MODE') > 0");
            $qb->setParameter('title', $data['title']);
        }
        if (isset($data['pubdate']) && $data['pubdate']) {
            $m = array();
            if (preg_match('/^\s*[0-9]{4}\s*$/', $data['pubdate'])) {
                $qb->andWhere("YEAR(STRTODATE(e.pubdate, '%Y')) = :year");
                $qb->setParameter('year', $data['pubdate']);
            } elseif (preg_match('/^\s*(\*|[0-9]{4})\s*-\s*(\*|[0-9]{4})\s*$/', $data['pubdate'], $m)) {
                $from = ($m[1] === '*' ? -1 : $m[1]);
                $to = ($m[2] === '*' ? 9999 : $m[2]);
                $qb->andWhere(":from <= YEAR(STRTODATE(e.pubdate, '%Y')) AND YEAR(STRTODATE(e.pubdate, '%Y')) <= :to");
                $qb->setParameter('from', $from);
                $qb->setParameter('to', $to);
            }
        }
        if (isset($data['location']) && $data['location']) {
            $qb->innerJoin('e.locationOfPrinting', 'g');
            $qb->andWhere("MATCH_AGAINST(g.alternatenames, g.name, :location 'IN BOOLEAN MODE') > 0");
            $qb->setParameter('location', $data['location']);
        }
        if (isset($data['format']) && is_array($data['format']) && count($data['format'])) {
            $qb->andWhere('e.format IN (:formats)');
            $qb->setParameter('formats', $data['format']);
        }
        if (isset($data['genre']) && is_array($data['genre']) && count($data['genre'])) {
            $qb->andWhere('e.genre IN (:genres)');
            $qb->setParameter('genres', $data['genre']);
        }
        if (isset($data['signed_author']) && $data['signed_author']) {
            $qb->andWhere("MATCH_AGAINST (e.signedAuthor, :signedAuthor 'IN BOOLEAN MODE') > 0");
            $qb->setParameter('signedAuthor', $data['signed_author']);
        }
        if (isset($data['imprint']) && $data['imprint']) {
            $qb->andWhere("MATCH_AGAINST (e.imprint, :imprint 'IN BOOLEAN MODE') > 0");
            $qb->setParameter('imprint', $data['imprint']);
        }
        if (isset($data['pseudonym']) && $data['pseudonym']) {
            $qb->andWhere("MATCH_AGAINST (e.pseudonym, :pseudonym 'IN BOOLEAN MODE') > 0");
            $qb->setParameter('pseudonym', $data['pseudonym']);
        }
        if (isset($data['orderby']) && $data['orderby']) {
            $dir = 'ASC';
            if (preg_match('/^(?:asc|desc)$/i', $data['orderdir'])) {
                $dir = $data['orderdir'];
            }
            switch ($data['orderby']) {
                case 'pubdate':
                    $qb->orderBy('e.pubdate', $dir);
                    break;
                case 'title':
                default:
                    $qb->orderBy('e.title', $dir);
                    break;
            }
        }

        if (isset($data['person_filter']) && count($data['person_filter']) > 0) {
            foreach ($data['person_filter'] as $idx => $filter) {
                $trAlias = 'tr_' . $idx;
                $pAlias = 'p_' . $idx;
                $qb->innerJoin('e.titleRoles', $trAlias)->innerJoin("{$trAlias}.person", $pAlias);
                if (isset($filter['name']) && $filter['name']) {
                    $qb->andWhere("MATCH_AGAINST ({$pAlias}.lastName, {$pAlias}.firstName, {$pAlias}.title, :{$pAlias}_name 'IN BOOLEAN MODE') > 0");
                    $qb->setParameter("{$pAlias}_name", $filter['name']);
                }
                if (isset($filter['gender']) && $filter['gender']) {
                    $genders = [];
                    if (in_array('M', $filter['gender'])) {
                        $genders[] = 'M';
                    }
                    if (in_array('F', $filter['gender'])) {
                        $genders[] = 'F';
                    }
                    if (in_array('U', $filter['gender'])) {
                        $genders[] = '';
                    }
                    $qb->andWhere("{$pAlias}.gender in (:genders)");
                    $qb->setParameter('genders', $genders);
                }

                if(isset($filter['person_role']) && count($filter['person_role']) > 0) {
                    $qb->andWhere("{$trAlias}.role in (:roles_{$idx})");
                    $qb->setParameter("roles_{$idx}", $filter['person_role']);
                }
            }
        }

        if(isset($data['firm_filter']) && count($data['firm_filter']) > 0) {
            foreach($data['firm_filter'] as $idx => $filter) {
                $tfrAlias = 'tfr_' . $idx;
                $fAlias = 'f_' . $idx;
                $qb->innerJoin('e.titleFirmroles', $tfrAlias)->innerJoin("{$tfrAlias}.firm", $fAlias);
                if(isset($filter['firm_name']) && $filter['firm_name']) {
                    $qb->andWhere("MATCH_AGAINST({$fAlias}.name, :{$fAlias}_name 'IN BOOLEAN MODE') > 0");
                    $qb->setParameter("{$fAlias}_name", $filter['firm_name']);
                }
                if(isset($filter['firm_role']) && count($filter['firm_role']) > 0) {
                    $qb->andWhere("{$tfrAlias}.firmrole in (:firmroles_{$idx})");
                    $qb->setParameter("firmroles_{$idx}", $filter['firm_role']);
                }
                if(isset($filter['firm_address']) && $filter['firm_address']) {
                    $qb->andWhere("MATCH_AGAINST({$fAlias}.streetAddress, :{$fAlias}_address 'IN BOOLEAN MODE') > 0");
                    $qb->setParameter("{$fAlias}_address", $filter['firm_address']);
                }
            }
        }

        return $qb->getQuery();
    }

    public function random($limit) {
        $qb = $this->createQueryBuilder('e');
        $qb->orderBy('RAND()');
        $qb->setMaxResults($limit);
        return $qb->getQuery()->execute();
    }
}
