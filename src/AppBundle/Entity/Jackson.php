<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jackson
 *
 * @ORM\Table(name="jackson_biblio",
 *  indexes={
 *      @ORM\Index(name="jackson_jbid_idx", columns={"jbid"}),
 *      @ORM\Index(name="jackson_author_ft", columns={"author"}, flags={"fulltext"}),
 *      @ORM\Index(name="jackson_title_ft", columns={"title"}, flags={"fulltext"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\JacksonRepository")
 */
class Jackson
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="jbid", type="integer")
     */
    private $jbid;

    /**
     * @var string
     * @ORM\Column(name="detailedentry", type="text")
     */
    private $detailedEntry;

    /**
     * @var string
     * @ORM\Column(name="author", type="text", nullable=true)
     */
    private $author;

    /**
     * @var string
     * @ORM\Column(name="title", type="text")
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="editor", type="text", nullable=true)
     */
    private $editor;

    /**
     * @var string
     * @ORM\Column(name="pubplace", type="string", length=160, nullable=true)
     */
    private $pubplace;

    /**
     * @var string
     * @ORM\Column(name="publisher", type="text", nullable=true)
     */
    private $publisher;

    /**
     * @var string
     * @ORM\Column(name="pubdate", type="text", nullable=true)
     */
    private $pubdate;

    /**
     * @var string
     * @ORM\Column(name="edition", type="string", length=160, nullable=true)
     */
    private $edition;

    /**
     * @var string
     * @ORM\Column(name="format", type="string", length=160, nullable=true)
     */
    private $format;

    /**
     * @var string
     * @ORM\Column(name="pagination", type="string", length=160, nullable=true)
     */
    private $pagination;

    /**
     * @var string
     * @ORM\Column(name="price", type="string", length=120, nullable=true)
     */
    private $price;

    /**
     * @var string
     * @ORM\Column(name="reference", type="string", length=120, nullable=true)
     */
    private $reference;

    /**
     * @var string
     * @ORM\Column(name="library", type="string", length=80, nullable=true)
     */
    private $library;

    /**
     * @var string
     * @ORM\Column(name="shelfmark", type="string", length=80, nullable=true)
     */
    private $shelfmark;

    /**
     * @var string
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var string
     * @ORM\Column(name="examnote", type="text")
     */
    private $examnote;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

