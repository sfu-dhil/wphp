<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jackson.
 *
 * @ORM\Table(name="jackson_biblio",
 *     indexes={
 *         @ORM\Index(name="jackson_jbid_idx", columns={"jbid"}),
 *         @ORM\Index(name="jackson_ft", columns={"author", "title"}, flags={"fulltext"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\JacksonRepository")
 */
class Jackson {
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(name="jbid", type="integer")
     */
    private int $jbid;

    /**
     * @ORM\Column(name="detailedentry", type="text")
     */
    private string $detailedEntry;

    /**
     * @ORM\Column(name="author", type="text", nullable=true)
     */
    private ?string $author = null;

    /**
     * @ORM\Column(name="title", type="text")
     */
    private string $title;

    /**
     * @ORM\Column(name="editor", type="text", nullable=true)
     */
    private ?string $editor = null;

    /**
     * @ORM\Column(name="pubplace", type="string", length=160, nullable=true)
     */
    private ?string  $pubplace = null;

    /**
     * @ORM\Column(name="publisher", type="text", nullable=true)
     */
    private ?string  $publisher = null;

    /**
     * @ORM\Column(name="pubdate", type="text", nullable=true)
     */
    private ?string  $pubdate = null;

    /**
     * @ORM\Column(name="edition", type="string", length=160, nullable=true)
     */
    private ?string  $edition = null;

    /**
     * @ORM\Column(name="format", type="string", length=160, nullable=true)
     */
    private ?string  $format = null;

    /**
     * @ORM\Column(name="pagination", type="string", length=160, nullable=true)
     */
    private ?string  $pagination = null;

    /**
     * @ORM\Column(name="price", type="string", length=120, nullable=true)
     */
    private ?string  $price = null;

    /**
     * @ORM\Column(name="reference", type="string", length=120, nullable=true)
     */
    private ?string $reference = null;

    /**
     * @ORM\Column(name="library", type="string", length=80, nullable=true)
     */
    private ?string  $library = null;

    /**
     * @ORM\Column(name="shelfmark", type="string", length=80, nullable=true)
     */
    private ?string $shelfmark = null;

    /**
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private ?string  $comment = null;

    /**
     * @ORM\Column(name="examnote", type="text")
     */
    private string $examnote;

    public function __toString() : string {
        return $this->title;
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function getJbid() : ?int {
        return $this->jbid;
    }

    public function setJbid(int $jbid) : self {
        $this->jbid = $jbid;

        return $this;
    }

    public function getDetailedEntry() : ?string {
        return $this->detailedEntry;
    }

    public function setDetailedEntry(string $detailedEntry) : self {
        $this->detailedEntry = $detailedEntry;

        return $this;
    }

    public function getAuthor() : ?string {
        return $this->author;
    }

    public function setAuthor(?string $author) : self {
        $this->author = $author;

        return $this;
    }

    public function getTitle() : ?string {
        return $this->title;
    }

    public function setTitle(string $title) : self {
        $this->title = $title;

        return $this;
    }

    public function getEditor() : ?string {
        return $this->editor;
    }

    public function setEditor(?string $editor) : self {
        $this->editor = $editor;

        return $this;
    }

    public function getPubplace() : ?string {
        return $this->pubplace;
    }

    public function setPubplace(?string $pubplace) : self {
        $this->pubplace = $pubplace;

        return $this;
    }

    public function getPublisher() : ?string {
        return $this->publisher;
    }

    public function setPublisher(?string $publisher) : self {
        $this->publisher = $publisher;

        return $this;
    }

    public function getPubdate() : ?string {
        return $this->pubdate;
    }

    public function setPubdate(?string $pubdate) : self {
        $this->pubdate = $pubdate;

        return $this;
    }

    public function getEdition() : ?string {
        return $this->edition;
    }

    public function setEdition(?string $edition) : self {
        $this->edition = $edition;

        return $this;
    }

    public function getFormat() : ?string {
        return $this->format;
    }

    public function setFormat(?string $format) : self {
        $this->format = $format;

        return $this;
    }

    public function getPagination() : ?string {
        return $this->pagination;
    }

    public function setPagination(?string $pagination) : self {
        $this->pagination = $pagination;

        return $this;
    }

    public function getPrice() : ?string {
        return $this->price;
    }

    public function setPrice(?string $price) : self {
        $this->price = $price;

        return $this;
    }

    public function getReference() : ?string {
        return $this->reference;
    }

    public function setReference(?string $reference) : self {
        $this->reference = $reference;

        return $this;
    }

    public function getLibrary() : ?string {
        return $this->library;
    }

    public function setLibrary(?string $library) : self {
        $this->library = $library;

        return $this;
    }

    public function getShelfmark() : ?string {
        return $this->shelfmark;
    }

    public function setShelfmark(?string $shelfmark) : self {
        $this->shelfmark = $shelfmark;

        return $this;
    }

    public function getComment() : ?string {
        return $this->comment;
    }

    public function setComment(?string $comment) : self {
        $this->comment = $comment;

        return $this;
    }

    public function getExamnote() : ?string {
        return $this->examnote;
    }

    public function setExamnote(string $examnote) : self {
        $this->examnote = $examnote;

        return $this;
    }
}
