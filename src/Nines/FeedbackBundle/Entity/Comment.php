<?php

namespace Nines\FeedbackBundle\Entity;

use AppBundle\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment
 *
 * @ORM\Table(name="comment", indexes={
 *  @ORM\Index(name="comment_ft_idx", 
 *      columns={"fullname", "content"}, 
 *      flags={"fulltext"}
 *  )
 * })
 * @ORM\Entity(repositoryClass="Nines\FeedbackBundle\Repository\CommentRepository")
 */
class Comment extends AbstractEntity {

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
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @var CommentStatus|null 
     * 
     * @ORM\ManyToOne(targetEntity="CommentStatus", inversedBy="comments")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id", nullable=false)
     */
    private $status;

    /**
     * @var Collection|CommentNote[] 
     * @ORM\OneToMany(targetEntity="CommentNote", mappedBy="comment")
     */
    private $notes;

    public function __construct() {
        $this->status = null;
        parent::__construct();
    }

    public function __toString() {
        return $this->content;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Comment
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     *
     * @return Comment
     */
    public function setFullname($fullname) {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string
     */
    public function getFullname() {
        return $this->fullname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Comment
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set followUp
     *
     * @param boolean $followUp
     *
     * @return Comment
     */
    public function setFollowUp($followUp) {
        $this->followUp = $followUp;

        return $this;
    }

    /**
     * Get followUp
     *
     * @return boolean
     */
    public function getFollowUp() {
        return $this->followUp;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Comment
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set entity
     *
     * @param string $entity
     *
     * @return Comment
     */
    public function setEntity($entity) {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return string
     */
    public function getEntity() {
        return $this->entity;
    }

    /**
     * Set status
     *
     * @param \Nines\FeedbackBundle\Entity\CommentStatus $status
     *
     * @return Comment
     */
    public function setStatus(\Nines\FeedbackBundle\Entity\CommentStatus $status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \Nines\FeedbackBundle\Entity\CommentStatus
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Add note
     *
     * @param \Nines\FeedbackBundle\Entity\CommentNote $note
     *
     * @return Comment
     */
    public function addNote(\Nines\FeedbackBundle\Entity\CommentNote $note) {
        $this->notes[] = $note;

        return $this;
    }

    /**
     * Remove note
     *
     * @param \Nines\FeedbackBundle\Entity\CommentNote $note
     */
    public function removeNote(\Nines\FeedbackBundle\Entity\CommentNote $note) {
        $this->notes->removeElement($note);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotes() {
        return $this->notes;
    }

}
