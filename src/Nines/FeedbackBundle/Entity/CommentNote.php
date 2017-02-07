<?php

namespace Nines\FeedbackBundle\Entity;

use AppBundle\Entity\AbstractEntity;
use Nines\UserBundle\Entity\User;
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
 * @ORM\Entity(repositoryClass="Nines\FeedbackBundle\Repository\CommentNoteRepository")
 */
class CommentNote extends AbstractEntity
{

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Nines\UserBundle\Entity\User")
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
     * @param \Nines\FeedbackBundle\Entity\Comment $comment
     *
     * @return CommentNote
     */
    public function setComment(\Nines\FeedbackBundle\Entity\Comment $comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \Nines\FeedbackBundle\Entity\Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set user
     *
     * @param \Nines\UserBundle\Entity\User $user
     *
     * @return CommentNote
     */
    public function setUser(\Nines\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Nines\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
