<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * English Novel bibliographic entry.
 *
 * @ORM\Table(name="en",
 *  indexes={
 *      @ORM\Index(name="en_author_ft", columns={"author"}, flags={"fulltext"}),
 *      @ORM\Index(name="en_title_ft", columns={"title"}, flags={"fulltext"})
 *  },
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="en_enid_uniq", columns={"en_id"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EnRepository")
 */
class En
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
     * @var string
     * @ORM\Column(name="en_id", type="string", length=20)
     */
    private $enId;

    /**
     * @var string
     * @ORM\Column(name="year", type="integer")
     * @Assert\Range(min=0, max=2018)
     */
    private $year;

    /**
     * @var string
     * @ORM\Column(name="author", type="string", length=160)
     */
    private $author;

    /**
     * @var string
     * @ORM\Column(name="editor", type="string", length=160, nullable=true)
     */
    private $editor;

    /**
     * @var string
     * @ORM\Column(name="translator", type="string", length=160, nullable=true)
     */
    private $translator;

    /**
     * @var string
     * @ORM\Column(name="title", type="text")
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="publish_place", type="string", length=100)
     */
    private $publishPlace;

    /**
     * @var string
     * @ORM\Column(name="imprint_details", type="text")
     */
    private $imprint;

    /**
     * @var string
     * @ORM\Column(name="pagination", type="string", length=100)
     */
    private $pagination;

    /**
     * @var string
     * @ORM\Column(name="format", type="string", length=8)
     */
    private $format;

    /**
     * @var string
     * @ORM\Column(name="price", type="string", length=100)
     */
    private $price;

    /**
     * @var string
     * @ORM\Column(name="contemporary", type="string", length=200)
     */
    private $contemporary;

    /**
     * @var string
     * @ORM\Column(name="shelfmark", type="string", length=200)
     */
    private $shelfmark;

    /**
     * @var string
     * @ORM\Column(name="further_editions", type="text", nullable=true)
     */
    private $editions;

    /**
     * @var string
     * @ORM\Column(name="genre", type="string", length=64, nullable=true)
     */
    private $genre;

    /**
     * @var string
     * @ORM\Column(name="gender", type="string", length=1, nullable=true)
     */
    private $gender;

    /**
     * @var string
     * @ORM\Column(name="notes", type="text")
     */
    private $notes;

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

