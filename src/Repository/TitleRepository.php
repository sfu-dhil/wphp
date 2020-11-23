<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Title;
use App\Entity\TitleSource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Title Repository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TitleRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Title::class);
    }

    /**
     * @param array $data
     * @param string $fieldName
     * @param string $formName
     */
    private function fulltextPart(QueryBuilder $qb, $data, $fieldName, $formName) : void {
        if ( ! isset($data[$formName])) {
            return;
        }
        $term = trim($data[$formName]);
        if ( ! $term) {
            return;
        }

        $m = [];
        if (preg_match('/^"(.*)"$/u', $term, $m)) {
            $qb->andWhere("e.{$fieldName} like :{$fieldName}Exact");
            $qb->setParameter("{$fieldName}Exact", "%{$m[1]}%");
        } else {
            $qb->andWhere("MATCH (e.{$fieldName}) AGAINST(:{$fieldName} BOOLEAN) > 0");
            $qb->setParameter($fieldName, $term);
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param array $data
     * @param string $fieldName
     * @param string $formName
     */
    private function arrayPart($qb, $data, $fieldName, $formName) : void {
        if ( ! isset($data[$formName]) || ! is_array($data[$formName])) {
            return;
        }
        $list = $data[$formName];
        if ( ! count($list)) {
            return;
        }
        $qb->andWhere("e.{$fieldName} IN (:{$fieldName})");
        $qb->setParameter($fieldName, $list);
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
        $qb->where('e.title LIKE :q');
        $qb->orWhere('e.id = :id');
        $qb->orderBy('e.title');
        $qb->setParameter('id', $q);
        $qb->setParameter('q', "%{$q}%");

        return $qb->getQuery()->execute();
    }

    /**
     * Build a complex search query from form data.
     *
     * @param array $data
     * @param null|mixed $user
     *
     * @return Query
     */
    public function buildSearchQuery($data = [], $user = null) {
        $qb = $this->createQueryBuilder('e');
        $qb->orderBy('e.pubdate');
        $qb->addOrderBy('e.title');
        $this->fulltextPart($qb, $data, 'title', 'title');
        if (isset($data['order']) && $data['order']) {
            switch ($data['order']) {
                case 'title_asc':
                    $qb->orderBy('e.title', 'ASC');
                    $qb->addOrderBy('e.pubdate');

                    break;
                case 'title_desc':
                    $qb->orderBy('e.title', 'DESC');
                    $qb->addOrderBy('e.pubdate');

                    break;
                case 'pubdate_asc':
                    $qb->orderBy('e.pubdate', 'ASC');
                    $qb->addOrderBy('e.title');

                    break;
                case 'pubdate_desc':
                    $qb->orderBy('e.pubdate', 'DESC');
                    $qb->addOrderBy('e.title');

                    break;
                case 'first_pubdate_asc':
                    $qb->orderBy('e.dateOfFirstPublication', 'ASC');
                    $qb->addOrderBy('e.title');

                    break;
                case 'first_pubdate_desc':
                    $qb->orderBy('e.dateOfFirstPublication', 'DESC');
                    $qb->addOrderBy('e.title');

                    break;
                case 'edition_asc':
                    $qb->orderBy('e.editionNumber', 'ASC');
                    $qb->orderBy('e.edition', 'ASC');
                    $qb->addOrderBy('e.title');

                    break;
                case 'edition_desc':
                    $qb->orderBy('e.editionNumber', 'DESC');
                    $qb->orderBy('e.edition', 'DESC');
                    $qb->addOrderBy('e.title');

                    break;
            }
        }
        if (isset($data['id']) && $data['id']) {
            $qb->andWhere('e.id = :id');
            $qb->setParameter('id', $data['id']);
        }
        if (isset($data['edition']) && $data['edition']) {
            if (preg_match('/^\s*[0-9]+\s*$/', $data['edition'])) {
                $qb->andWhere('e.editionNumber = :editionNumber');
                $qb->setParameter('editionNumber', $data['editionNumber']);
            } else {
                $qb->andWhere('MATCH(e.edition) AGAINST(:edition) > 0');
                $qb->setParameter('edition', $data['edition']);
            }
        }
        if (isset($data['volumes']) && $data['volumes']) {
            $qb->andWhere('e.volumes = :volumes');
            $qb->setParameter('volumes', $data['volumes']);
        }
        if (isset($data['sizeW']) && $data['sizeW']) {
            $m = [];
            if (preg_match('/^\s*[0-9]+\s*$/', $data['sizeW'])) {
                $qb->andWhere('e.sizeW = :sizeW');
                $qb->setParameter('sizeW', $data['sizeW']);
            } elseif (preg_match('/^\s*(\*|[0-9]+)\s*-\s*(\*|[0-9]+)\s*$/', $data['sizeW'], $m)) {
                $from = ('*' === $m[1] ? 0 : $m[1]);
                $to = ('*' === $m[2] ? 9999 : $m[2]);
                $qb->andWhere('(:sizeW_from <= e.sizeW) AND (e.sizeW <= :sizeW_to)');
                $qb->setParameter('sizeW_from', $from);
                $qb->setParameter('sizeW_to', $to);
            }
        }
        if (isset($data['sizeL']) && $data['sizeL']) {
            $m = [];
            if (preg_match('/^\s*[0-9]+\s*$/', $data['sizeL'])) {
                $qb->andWhere('e.sizeL = :sizeL');
                $qb->setParameter('sizeL', $data['sizeL']);
            } elseif (preg_match('/^\s*(\*|[0-9]+)\s*-\s*(\*|[0-9]+)\s*$/', $data['sizeL'], $m)) {
                $from = ('*' === $m[1] ? 0 : $m[1]);
                $to = ('*' === $m[2] ? 9999 : $m[2]);
                $qb->andWhere('(:sizeL_from <= e.sizeL) AND (e.sizeL <= :sizeL_to)');
                $qb->setParameter('sizeL_from', $from);
                $qb->setParameter('sizeL_to', $to);
            }
        }
        if ($user) {
            if (isset($data['checked'])) {
                $qb->andWhere('e.checked = :checked');
                $qb->setParameter('checked', 'Y' === $data['checked']);
            }
            if (isset($data['finalcheck'])) {
                $qb->andWhere('e.finalcheck = :finalcheck');
                $qb->setParameter('finalcheck', 'Y' === $data['finalcheck']);
            }
            if (isset($data['finalattempt'])) {
                $qb->andWhere('e.finalattempt = :finalattempt');
                $qb->setParameter('finalattempt', 'Y' === $data['finalattempt']);
            }
        } else {
            $qb->andWhere('e.finalcheck = 1 OR e.finalattempt = 1');
        }
        if (isset($data['pubdate']) && $data['pubdate']) {
            $m = [];
            if (preg_match('/^\s*[0-9]{4}\s*$/', $data['pubdate'])) {
                $qb->andWhere("YEAR(STRTODATE(e.pubdate, '%Y')) = :year");
                $qb->setParameter('year', $data['pubdate']);
            } elseif (preg_match('/^\s*(\*|[0-9]{4})\s*-\s*(\*|[0-9]{4})\s*$/', $data['pubdate'], $m)) {
                $from = ('*' === $m[1] ? -1 : $m[1]);
                $to = ('*' === $m[2] ? 9999 : $m[2]);
                $qb->andWhere(":from <= YEAR(STRTODATE(e.pubdate, '%Y')) AND YEAR(STRTODATE(e.pubdate, '%Y')) <= :to");
                $qb->setParameter('from', $from);
                $qb->setParameter('to', $to);
            }
        }
        if (isset($data['date_of_first_publication']) && $data['date_of_first_publication']) {
            $m = [];
            if (preg_match('/^\s*[0-9]{4}\s*$/', $data['date_of_first_publication'])) {
                $qb->andWhere("YEAR(STRTODATE(e.dateOfFirstPublication, '%Y')) = :year");
                $qb->setParameter('year', $data['date_of_first_publication']);
            } elseif (preg_match('/^\s*(\*|[0-9]{4})\s*-\s*(\*|[0-9]{4})\s*$/', $data['date_of_first_publication'], $m)) {
                $from = ('*' === $m[1] ? -1 : $m[1]);
                $to = ('*' === $m[2] ? 9999 : $m[2]);
                $qb->andWhere(":from <= YEAR(STRTODATE(e.dateOfFirstPublication, '%Y')) AND YEAR(STRTODATE(e.dateOfFirstPublication, '%Y')) <= :to");
                $qb->setParameter('from', $from);
                $qb->setParameter('to', $to);
            }
        }
        if (isset($data['location']) && $data['location']) {
            $qb->innerJoin('e.locationOfPrinting', 'g');
            $qb->andWhere('MATCH(g.alternatenames, g.name) AGAINST (:location BOOLEAN) > 0');
            $qb->setParameter('location', $data['location']);
        }
        $this->arrayPart($qb, $data, 'format', 'format');

        if (null !== $data['price_filter']['price_pound'] ||
            null !== $data['price_filter']['price_shilling'] ||
            null !== $data['price_filter']['price_pence']) {
            $total = $data['price_filter']['price_pound'] * 240
                + $data['price_filter']['price_shilling'] * 12
                + $data['price_filter']['price_pence'];
            $operator = '<';
            switch ($data['price_filter']['price_comparison']) {
                case 'eq':
                    $operator = '=';

                    break;
                case 'lt':
                    $operator = '<';

                    break;
                case 'gt':
                    $operator = '>';

                    break;
            }
            $qb->andWhere("e.totalPrice {$operator} :total");
            $qb->andWhere('e.totalPrice > 0');
            $qb->setParameter('total', $total);
        }

        $this->arrayPart($qb, $data, 'genre', 'genre');

        $this->fulltextPart($qb, $data, 'signedAuthor', 'signed_author');
        $this->fulltextPart($qb, $data, 'imprint', 'imprint');
        $this->fulltextPart($qb, $data, 'copyright', 'copyright');
        $this->fulltextPart($qb, $data, 'colophon', 'colophon');
        $this->fulltextPart($qb, $data, 'pseudonym', 'pseudonym');
        $this->fulltextPart($qb, $data, 'shelfmark', 'shelfmark');
        $this->fulltextPart($qb, $data, 'notes', 'notes');

        if (isset($data['self_published']) && $data['self_published']) {
            if ($data['self_published']) {
                $qb->andWhere('e.selfpublished = 1');
            }
        }

        // only add the title filter query parts if the subform has data.
        if (isset($data['person_filter']) && count(array_filter($data['person_filter']))) {
            $filter = $data['person_filter'];
            $idx = '00';
            $trAlias = 'tr_' . $idx;
            $pAlias = 'p_' . $idx;
            $qb->innerJoin('e.titleRoles', $trAlias)->innerJoin("{$trAlias}.person", $pAlias);

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

            if (isset($filter['person_role']) && count($filter['person_role']) > 0) {
                $qb->andWhere("{$trAlias}.role in (:roles_{$idx})");
                $qb->setParameter("roles_{$idx}", $filter['person_role']);
            }
        }

        // only add the firm filter query parts if the subform has data.
        if (isset($data['firm_filter']) && count(array_filter($data['firm_filter']))) {
            $filter = $data['firm_filter'];
            $idx = '01';
            $tfrAlias = 'tfr_' . $idx;
            $fAlias = 'f_' . $idx;
            $qb->innerJoin('e.titleFirmroles', $tfrAlias)->innerJoin("{$tfrAlias}.firm", $fAlias);

            if (isset($filter['firm_id']) && $filter['firm_id']) {
                $qb->andWhere("{$fAlias}.id = :firm_id");
                $qb->setParameter('firm_id', $filter['firm_id']);
            }
            if (isset($filter['firm_name']) && $filter['firm_name']) {
                $qb->andWhere("MATCH({$fAlias}.name) AGAINST(:{$fAlias}_name BOOLEAN) > 0");
                $qb->setParameter("{$fAlias}_name", $filter['firm_name']);
            }
            if (isset($filter['firm_role']) && count($filter['firm_role']) > 0) {
                $qb->andWhere("{$tfrAlias}.firmrole in (:firmroles_{$idx})");
                $qb->setParameter("firmroles_{$idx}", $filter['firm_role']);
            }
            if (isset($filter['firm_address']) && $filter['firm_address']) {
                $qb->andWhere("MATCH({$fAlias}.streetAddress) AGAINST(:{$fAlias}_address BOOLEAN) > 0");
                $qb->setParameter("{$fAlias}_address", $filter['firm_address']);
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
                $qb->andWhere("{$fAlias}.gender in (:genders)");
                $qb->setParameter('genders', $genders);
            }
        }

        if (isset($data['titlesource_filter']) && $data['titlesource_filter']) {
            /** @var TitleSource $filter */
            $filter = $data['titlesource_filter'];
            $qb->innerJoin('e.titleSources', 'ts');
            if ($filter->getSource()) {
                $qb->andWhere('ts.source = :source');
                $qb->setParameter('source', $filter->getSource());
            }
            if ($filter->getIdentifier()) {
                $qb->andWhere('MATCH(ts.identifier) AGAINST(:identifier BOOLEAN) > 0');
                $qb->setParameter('identifier', $filter->getIdentifier());
            }
        }

        return $qb->getQuery();
    }

    /**
     * Find and return $limit random titles.
     *
     * @param int $limit
     *
     * @return Collection
     */
    public function random($limit) {
        $qb = $this->createQueryBuilder('e');
        $qb->orderBy('RAND()');
        $qb->setMaxResults($limit);

        return $qb->getQuery()->execute();
    }
}
