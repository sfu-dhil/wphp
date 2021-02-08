<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Repository\RelatedTitleRepository;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * @ORM\Entity(repositoryClass=RelatedTitleRepository::class)
 */
class RelatedTitle extends AbstractEntity {
    /**
     * @var Title
     * @ORM\ManyToOne(targetEntity="App\Entity\Title", inversedBy="sourceTitles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sourceTitle;

    /**
     * @var Title
     * @ORM\ManyToOne(targetEntity="App\Entity\Title", inversedBy="relatedTitles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $relatedTitle;

    /**
     * @var TitleRelationship
     * @ORM\ManyToOne(targetEntity="App\Entity\TitleRelationship", inversedBy="relatedTitles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $titleRelationship;

    public function __construct() {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    public function __toString() : string {
        return $this->sourceTitle . ' ' . $this->titleRelationship . ' ' . $this->relatedTitle;
    }

    public function getSourceTitle() : ?Title {
        return $this->sourceTitle;
    }

    public function setSourceTitle(?Title $sourceTitle) : self {
        $this->sourceTitle = $sourceTitle;

        return $this;
    }

    public function getRelatedTitle() : ?Title {
        return $this->relatedTitle;
    }

    public function setRelatedTitle(?Title $relatedTitle) : self {
        $this->relatedTitle = $relatedTitle;

        return $this;
    }

    public function getTitleRelationship() : ?TitleRelationship {
        return $this->titleRelationship;
    }

    public function setTitleRelationship(?TitleRelationship $titleRelationship) : self {
        $this->titleRelationship = $titleRelationship;

        return $this;
    }
}
