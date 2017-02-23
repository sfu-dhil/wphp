<?php

namespace Nines\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * PostCategory
 *
 * @ORM\Table(name="blog_post_category")
 * @ORM\Entity(repositoryClass="Nines\BlogBundle\Repository\PostCategoryRepository")
 */
class PostCategory extends AbstractEntity
{
    /**
     * Name of the category.
     * @var string
     * @ORM\Column(type="string", length=120)
     */
    private $name;

    /**
     * Category label.
     * @var string
     * @ORM\Column(type="string", length=120)
     */
    private $label;

    /**
     * Description of the post.
     * @var string
     * @ORM\Column(type="text")
     */
    private $description;
    
    /**
     * Posts in the category.
     * @var Collection|Post[]
     * @ORM\OneToMany(targetEntity="Post", mappedBy="category")
     */
    private $posts;

    /**
     * Construct the category.
     */
    public function __construct() {
        parent::__construct();
        $this->posts = new ArrayCollection();
    }
    
    /**
     * Return the label.
     * 
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
     * @param Post $post
     *
     * @return PostCategory
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
}
