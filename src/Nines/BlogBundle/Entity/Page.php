<?php

namespace Nines\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UserBundle\Entity\User;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Page
 *
 * @ORM\Table(name="page", indexes={
 *   @ORM\Index(name="blog_page_content", columns={"title","searchable"}, flags={"fulltext"})
 * })
 * @ORM\Entity(repositoryClass="Nines\BlogBundle\Repository\PageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Page extends AbstractEntity {

    /**
     * @var int
     * @ORM\Column(name="weight", type="integer", nullable=false)
     */
    private $weight;
    
    /**
     *
     * @var boolean
     * @ORM\Column(name="public", type="boolean")
     */
    private $public;

    /**
     * Blog post title.
     *
     * @var string
     * 
     * @ORM\Column(name="title", type="string", nullable=false)
     */
    private $title;

    /**
     * An excerpt, to display in lists.
     *
     * @var string
     * 
     * @ORM\Column(name="excerpt", type="text", nullable=true)
     */
    private $excerpt;
    
    /**
     * The content of the post, as HTML.
     *
     * @var string
     * 
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    private $content;

    /**
     * Searchable version of the content, with the tags stripped out.
     * 
     * @var string
     * 
     * @ORM\Column(name="searchable", type="text", nullable=false)
     */
    private $searchable;

    /**
     * User that created the post.
     * 
     * @var User
     * @ORM\ManyToOne(targetEntity="Nines\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct() {
        parent::__construct();
        $this->weight = 0;
        $this->public = false;
    }
    
    /**
     * Build a searchable version of the text.
     * 
     * @todo Refactor this into a service.
     * 
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public final function buildSearchableText() {
        $plain = strip_tags($this->content);
        $converted = mb_convert_encoding($plain, 'UTF-8', 'HTML-ENTITIES');
        $trimmed = preg_replace("/(^\s+)|(\s+$)/u", "", $converted);
        // \xA0 is the result of converting nbsp.
        $normalized = preg_replace("/[[:space:]\x{A0}]/su", " ", $trimmed);
        $this->searchable = $normalized;
    }
    
    /**
     * Find the keyword in the searchable text and highlight it. Returns a list
     * of the higlights as KWIC results.
     * 
     * @todo Refactor this into a service.
     * 
     * @param string $keyword
     * @return array
     */
    public function searchHighlight($keyword) {
        $i = stripos($this->searchable, $keyword);
        $results = array();
        while($i !== false) {
            $s = substr($this->searchable, max([0, $i - 60]), 120);
            $results[] = preg_replace("/$keyword/", "<mark>{$keyword}</mark>", $s);
            $i = stripos($this->searchable, $keyword, $i+1);
        }
        return $results;
    }

    /**
     * Set public
     *
     * @param boolean $public
     *
     * @return Page
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

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Page
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
     * @return Page
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
     * Set searchable
     *
     * @param string $searchable
     *
     * @return Page
     */
    public function setSearchable($searchable)
    {
        $this->searchable = $searchable;

        return $this;
    }

    /**
     * Get searchable
     *
     * @return string
     */
    public function getSearchable()
    {
        return $this->searchable;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Page
     */
    public function setUser(User $user)
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

    public function __toString() {
        return $this->title;
    }


    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return Page
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set excerpt
     *
     * @param string $excerpt
     *
     * @return Page
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    /**
     * Get excerpt
     *
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }
}
