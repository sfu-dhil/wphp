<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jackson.
 *
 * @ORM\Table(name="jackson_biblio",
 *  indexes={
 *      @ORM\Index(name="jackson_jbid_idx", columns={"jbid"}),
 *      @ORM\Index(name="jackson_ft", columns={"author", "title"}, flags={"fulltext"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\JacksonRepository")
 */
class Jackson {
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

    public function __toString() : string {
        return $this->title;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set jbid.
     *
     * @param int $jbid
     *
     * @return Jackson
     */
    public function setJbid($jbid) {
        $this->jbid = $jbid;

        return $this;
    }

    /**
     * Get jbid.
     *
     * @return int
     */
    public function getJbid() {
        return $this->jbid;
    }

    /**
     * Set detailedEntry.
     *
     * @param string $detailedEntry
     *
     * @return Jackson
     */
    public function setDetailedEntry($detailedEntry) {
        $this->detailedEntry = $detailedEntry;

        return $this;
    }

    /**
     * Get detailedEntry.
     *
     * @return string
     */
    public function getDetailedEntry() {
        return $this->detailedEntry;
    }

    /**
     * Set author.
     *
     * @param string $author
     *
     * @return Jackson
     */
    public function setAuthor($author) {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return string
     */
    public function getAuthor() {
        return $this->author;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Jackson
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set editor.
     *
     * @param string $editor
     *
     * @return Jackson
     */
    public function setEditor($editor) {
        $this->editor = $editor;

        return $this;
    }

    /**
     * Get editor.
     *
     * @return string
     */
    public function getEditor() {
        return $this->editor;
    }

    /**
     * Set pubplace.
     *
     * @param string $pubplace
     *
     * @return Jackson
     */
    public function setPubplace($pubplace) {
        $this->pubplace = $pubplace;

        return $this;
    }

    /**
     * Get pubplace.
     *
     * @return string
     */
    public function getPubplace() {
        return $this->pubplace;
    }

    /**
     * Set publisher.
     *
     * @param string $publisher
     *
     * @return Jackson
     */
    public function setPublisher($publisher) {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * Get publisher.
     *
     * @return string
     */
    public function getPublisher() {
        return $this->publisher;
    }

    /**
     * Set pubdate.
     *
     * @param string $pubdate
     *
     * @return Jackson
     */
    public function setPubdate($pubdate) {
        $this->pubdate = $pubdate;

        return $this;
    }

    /**
     * Get pubdate.
     *
     * @return string
     */
    public function getPubdate() {
        return $this->pubdate;
    }

    /**
     * Set edition.
     *
     * @param string $edition
     *
     * @return Jackson
     */
    public function setEdition($edition) {
        $this->edition = $edition;

        return $this;
    }

    /**
     * Get edition.
     *
     * @return string
     */
    public function getEdition() {
        return $this->edition;
    }

    /**
     * Set format.
     *
     * @param string $format
     *
     * @return Jackson
     */
    public function setFormat($format) {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format.
     *
     * @return string
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     * Set pagination.
     *
     * @param string $pagination
     *
     * @return Jackson
     */
    public function setPagination($pagination) {
        $this->pagination = $pagination;

        return $this;
    }

    /**
     * Get pagination.
     *
     * @return string
     */
    public function getPagination() {
        return $this->pagination;
    }

    /**
     * Set price.
     *
     * @param string $price
     *
     * @return Jackson
     */
    public function setPrice($price) {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return string
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * Set reference.
     *
     * @param string $reference
     *
     * @return Jackson
     */
    public function setReference($reference) {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference.
     *
     * @return string
     */
    public function getReference() {
        return $this->reference;
    }

    /**
     * Set library.
     *
     * @param string $library
     *
     * @return Jackson
     */
    public function setLibrary($library) {
        $this->library = $library;

        return $this;
    }

    /**
     * Get library.
     *
     * @return string
     */
    public function getLibrary() {
        return $this->library;
    }

    /**
     * Set shelfmark.
     *
     * @param string $shelfmark
     *
     * @return Jackson
     */
    public function setShelfmark($shelfmark) {
        $this->shelfmark = $shelfmark;

        return $this;
    }

    /**
     * Get shelfmark.
     *
     * @return string
     */
    public function getShelfmark() {
        return $this->shelfmark;
    }

    /**
     * Set comment.
     *
     * @param string $comment
     *
     * @return Jackson
     */
    public function setComment($comment) {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string
     */
    public function getComment() {
        return $this->comment;
    }

    /**
     * Set examnote.
     *
     * @param string $examnote
     *
     * @return Jackson
     */
    public function setExamnote($examnote) {
        $this->examnote = $examnote;

        return $this;
    }

    /**
     * Get examnote.
     *
     * @return string
     */
    public function getExamnote() {
        return $this->examnote;
    }
}
