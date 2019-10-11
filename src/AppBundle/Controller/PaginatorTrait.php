<?php

namespace AppBundle\Controller;

use Knp\Component\Pager\Paginator;

/**
 * Convienence trait for the use of paginators.
 */
trait PaginatorTrait {
    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * Set the paginator service.
     *
     * @param Paginator $paginator
     */
    public function setPaginator(Paginator $paginator) {
        $this->paginator = $paginator;
    }
}
