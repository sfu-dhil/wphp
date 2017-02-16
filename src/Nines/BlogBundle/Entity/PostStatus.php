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
     * @var Collection|Post[]
     * @ORM\OneToMany(targetEntity="Post", mappedBy="status")
     */
    private $posts;

    public function __construct() {
        parent::__construct();
        $this->posts = new ArrayCollection();
    }
    
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
     * @param \Nines\BlogBundle\Entity\Post $post
     *
     * @return PostStatus
     */
    public function addPost(\Nines\BlogBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \Nines\BlogBundle\Entity\Post $post
     */
    public function removePost(\Nines\BlogBundle\Entity\Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }
}
