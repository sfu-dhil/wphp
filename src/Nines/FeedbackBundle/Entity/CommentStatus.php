<?php

namespace Nines\FeedbackBundle\Entity;

use Nines\UtilBundle\Entity\AbstractEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CommentStatus
 *
 * @ORM\Table(name="comment_status")
 * @ORM\Entity(repositoryClass="Nines\FeedbackBundle\Repository\CommentStatusRepository")
 */
class CommentStatus extends AbstractEntity {

    /**
     * Name of the status.
     * @var string
     * @ORM\Column(type="string", length=120)
     */
    private $name;

    /**
     * Human-readable label.
     * @var string
     * 
     * @ORM\Column(type="string", length=120)
     */
    private $label;

    /**
     * Description of the status.
     * @var string
     * 
     * @ORM\Column(type="text")
     */
    private $description;
    
    /**
     * List of the comments with this status.
     * 
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="status")
     * @var Collection|Comment[]
     */
    private $comments;
    
    public function __construct() {
        parent::__construct();
        $this->comments = new ArrayCollection();
    }
    
    /**
     * Get the name.
     * 
     * @return string
     */
    function getName() {
        return $this->name;
    }

    /**
     * Get the label.
     * 
     * @return string
     */
    function getLabel() {
        return $this->label;
    }

    /**
     * Get the description.
     * 
     * @return string
     */
    function getDescription() {
        return $this->description;
    }

    /**
     * Set the name.
     * @param string $name
     * @return CommentStatus
     */
    function setName($name) {
        $this->name = $name;
    }

    /**
     * Set the label.
     * @param string label
     * @return CommentStatus
     */
    function setLabel($label) {
        $this->label = $label;
    }

    /**
     * Set the description.
     * @param string $description
     * @return CommentStatus
     */
    function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Return a string representation of the status.
     * 
     * @return string
     */
    public function __toString() {
        return $this->label;
    }

}
