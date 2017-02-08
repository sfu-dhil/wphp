<?php

namespace Nines\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * PostCategory
 *
 * @ORM\Table(name="blog_post_category")
 * @ORM\Entity(repositoryClass="Nines\BlogBundle\Repository\PostCategoryRepository")
 */
class PostCategory extends AbstractEntity
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
     * @ORM\OneToMany(targetEntity="Post", mappedBy="category")
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
     * @return PostCategory
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
     * @return PostCategory
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
     * @return PostCategory
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
     * @return PostCategory
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
