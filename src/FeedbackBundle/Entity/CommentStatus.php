<?php

namespace FeedbackBundle\Entity;

use AppBundle\Entity\AbstractEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CommentType
 *
 * @ORM\Table(name="comment_type")
 * @ORM\Entity(repositoryClass="FeedbackBundle\Repository\CommentStatusRepository")
 */
class CommentStatus extends AbstractEntity {

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $label;

    /**
     * @ORM\Column(type="text")
     */
    private $description;
    
    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="status")
     * @var Collection|Comment[]
     */
    private $comments;
    
    public function __construct() {
        parent::__construct();
        $this->comments = new ArrayCollection();
    }
    
    function getName() {
        return $this->name;
    }

    function getLabel() {
        return $this->label;
    }

    function getDescription() {
        return $this->description;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setLabel($label) {
        $this->label = $label;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    public function __toString() {
        return $this->label;
    }

}
