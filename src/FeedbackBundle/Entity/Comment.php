<?php

namespace FeedbackBundle\Entity;

use AppBundle\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use FeedbackBundle\Repository\CommentRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="CommentRepository")
 */
class Comment extends AbstractEntity
{

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $fullname;
    
    /**
     * @ORM\Column(type="string", length=120)
     * @Assert\Email()
     */
    private $email;
    
    /**
     * @ORM\Column(type="boolean")
     * @var type 
     */
    private $followUp;
    
    /**
     * A string of the form entity:id where entity is the un-namespaced
     * class name in lowercase and id is the numeric id.
     * @ORM\Column(type="string", length=120)
     */
    private $entity;
    
    /**
     * @ORM\Column(type="string", length=120)
     */
    private $content;
    
    public function __toString() {
        return $this->title;
    }


    /**
     * Set title
     *
     * @param string $title
     *
     * @return Comment
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     *
     * @return Comment
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Comment
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set followUp
     *
     * @param boolean $followUp
     *
     * @return Comment
     */
    public function setFollowUp($followUp)
    {
        $this->followUp = $followUp;

        return $this;
    }

    /**
     * Get followUp
     *
     * @return boolean
     */
    public function getFollowUp()
    {
        return $this->followUp;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set entity
     *
     * @param string $entity
     *
     * @return Comment
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
