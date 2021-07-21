<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\TitleRelationship;
use Doctrine\Persistence\ManagerRegistry;
use Nines\UtilBundle\Repository\TermRepository;

/**
 * @method null|TitleRelationship find($id, $lockMode = null, $lockVersion = null)
 * @method null|TitleRelationship findOneBy(array $criteria, array $orderBy = null)
 * @method TitleRelationship[] findAll()
 * @method TitleRelationship[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TitleRelationshipRepository extends TermRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, TitleRelationship::class);
    }
}
