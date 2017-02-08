<?php

namespace Nines\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;
use Nines\UserBundle\Entity\User;

/**
 * Post
 *
 * @ORM\Table(name="blog_post")
 * @ORM\Entity(repositoryClass="Nines\BlogBundle\Repository\PostRepository")
 */
class Post extends AbstractEntity
{
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $title;

    /**
     *
     * @var string
     * 
     * @ORM\Column(name="content", type="text", nullable=false)
     */    
    private $content;
    
    /**
     * @var PostCategory
     *
     * @ORM\ManyToOne(targetEntity="PostCategory", inversedBy="posts")
     */
    private $category;

    /**
     * @var PostStatus
     * 
     * @ORM\ManyToOne(targetEntity="PostStatus", inversedBy="posts")
     */
    private $status;
    
    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Nines\UserBundle\Entity\User")
     */
    private $user;

    public function __toString() {
        return $this->title;
    }


    /**
     * Set title
     *
     * @param string $title
     *
     * @return Post
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
     * Set content
     *
     * @param string $content
     *
     * @return Post
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
     * Set category
     *
     * @param PostCategory $category
     *
     * @return Post
     */
    public function setCategory(\Nines\BlogBundle\Entity\PostCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return PostCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set status
     *
     * @param PostStatus $status
     *
     * @return Post
     */
    public function setStatus(PostStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return PostStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Post
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
