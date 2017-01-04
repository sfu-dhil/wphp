<?php

namespace FeedbackBundle\Entity;

use AppBundle\Entity\AbstractEntity;
use AppUserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * CommentNote
 *
 * @ORM\Table(name="comment_note", indexes={
 *  @ORM\Index(name="commentnote_ft_idx", 
 *      columns={"content"}, 
 *      flags={"fulltext"}
 *  )
 * })
 * @ORM\Entity(repositoryClass="FeedbackBundle\Repository\CommentNoteRepository")
 */
class CommentNote extends AbstractEntity
{

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppUserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;
    
    /**
     * @ORM\Column(type="text")
     */
    private $content;
    
    /**
     * @var Comment
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="notes")
     * @ORM\JoinColumn(name="comment_id", referencedColumnName="id", nullable=false)
     */
    private $comment;
    
    public function __toString() {
        return $this->content;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return CommentNote
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
     * Set comment
     *
     * @param \FeedbackBundle\Entity\Comment $comment
     *
     * @return CommentNote
     */
    public function setComment(\FeedbackBundle\Entity\Comment $comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \FeedbackBundle\Entity\Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set user
     *
     * @param \AppUserBundle\Entity\User $user
     *
     * @return CommentNote
     */
    public function setUser(\AppUserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppUserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
