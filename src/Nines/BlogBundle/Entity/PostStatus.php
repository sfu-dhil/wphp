<?php

namespace Nines\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * PostStatus
 *
 * @ORM\Table(name="blog_post_status")
 * @ORM\Entity(repositoryClass="Nines\BlogBundle\Repository\PostStatusRepository")
 */
class PostStatus extends AbstractEntity
{
    /**
     * Name of the status.
     * 
     * @ORM\Column(type="string", length=120)
     * @var string
     */
    private $name;

    /**
     * Human readable status label.
     * @var string
     * @ORM\Column(type="string", length=120)
     */
    private $label;
    
    /**
     * True if the status is meant to be public.
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $public;

    /**
     * Descriptino of the status.
     * @var title
     * @ORM\Column(type="text")
     */
    private $description;
    
    /**
     * List of the posts with this status.
     * 
     * @var Collection|Post[]
     * @ORM\OneToMany(targetEntity="Post", mappedBy="status")
     */
    private $posts;

    /**
     * Build the post.
     */
    public function __construct() {
        parent::__construct();
        $this->public = false;
        $this->posts = new ArrayCollection();
    }
    
    /**
     * Get the status label.
     * @return string
     */
    public function __toString() {
        return $this->label;
    }


    /**
     * Set name
     *
     * @param string $name
     *
     * @return PostStatus
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return PostStatus
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return PostStatus
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add post
     *
     * @param Post $post
     *
     * @return PostStatus
     */
    public function addPost(Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param Post $post
     */
    public function removePost(Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Set public
     *
     * @param boolean $public
     *
     * @return PostStatus
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }
}
