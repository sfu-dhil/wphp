<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use Knp\Component\Pager\Paginator;

/**
 * Description of PaginatorTrait
 */
trait PaginatorTrait {

    /**
     * @var Paginator
     */
    protected $paginator;

    public function setPaginator(Paginator $paginator) {
        $this->paginator = $paginator;
    }

}
