<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Repository\TitleRelationshipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * @ORM\Entity(repositoryClass=TitleRelationshipRepository::class)
 */
class TitleRelationship extends AbstractTerm {
    /**
     * @var Collection|RelatedTitle[]
     * @ORM\OneToMany(targetEntity="App\Entity\RelatedTitle", mappedBy="titleRelationship")
     */
    private $relatedTitles;

    public function __construct() {
        parent::__construct();
        $this->relatedTitles = new ArrayCollection();
    }

    /**
     * @return Collection|RelatedTitle[]
     */
    public function getRelatedTitles() : Collection {
        return $this->relatedTitles;
    }

    public function addRelatedTitle(RelatedTitle $relatedTitle) : self {
        if ( ! $this->relatedTitles->contains($relatedTitle)) {
            $this->relatedTitles[] = $relatedTitle;
            $relatedTitle->setTitleRelationship($this);
        }

        return $this;
    }

    public function removeRelatedTitle(RelatedTitle $relatedTitle) : self {
        if ($this->relatedTitles->removeElement($relatedTitle)) {
            // set the owning side to null (unless already changed)
            if ($relatedTitle->getTitleRelationship() === $this) {
                $relatedTitle->setTitleRelationship(null);
            }
        }

        return $this;
    }
}
