<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * English Novel bibliographic entry.
 *
 * @ORM\Table(name="en",
 *  indexes={
 *      @ORM\Index(name="en_ft", columns={"author","title"}, flags={"fulltext"})
 *  },
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="en_enid_uniq", columns={"en_id"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\EnRepository")
 */
class En {
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
     * @ORM\Column(name="price", type="string", length=100, nullable=true)
     */
    private $price;

    /**
     * @var string
     * @ORM\Column(name="contemporary", type="string", length=200, nullable=true)
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
     * Set enId.
     *
     * @param string $enId
     *
     * @return En
     */
    public function setEnId($enId) {
        $this->enId = $enId;

        return $this;
    }

    /**
     * Get enId.
     *
     * @return string
     */
    public function getEnId() {
        return $this->enId;
    }

    /**
     * Set year.
     *
     * @param int $year
     *
     * @return En
     */
    public function setYear($year) {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year.
     *
     * @return int
     */
    public function getYear() {
        return $this->year;
    }

    /**
     * Set author.
     *
     * @param string $author
     *
     * @return En
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
     * Set editor.
     *
     * @param string $editor
     *
     * @return En
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
     * Set translator.
     *
     * @param string $translator
     *
     * @return En
     */
    public function setTranslator($translator) {
        $this->translator = $translator;

        return $this;
    }

    /**
     * Get translator.
     *
     * @return string
     */
    public function getTranslator() {
        return $this->translator;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return En
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
     * Set publishPlace.
     *
     * @param string $publishPlace
     *
     * @return En
     */
    public function setPublishPlace($publishPlace) {
        $this->publishPlace = $publishPlace;

        return $this;
    }

    /**
     * Get publishPlace.
     *
     * @return string
     */
    public function getPublishPlace() {
        return $this->publishPlace;
    }

    /**
     * Set imprint.
     *
     * @param string $imprint
     *
     * @return En
     */
    public function setImprint($imprint) {
        $this->imprint = $imprint;

        return $this;
    }

    /**
     * Get imprint.
     *
     * @return string
     */
    public function getImprint() {
        return $this->imprint;
    }

    /**
     * Set pagination.
     *
     * @param string $pagination
     *
     * @return En
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
     * Set format.
     *
     * @param string $format
     *
     * @return En
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
     * Set price.
     *
     * @param string $price
     *
     * @return En
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
     * Set contemporary.
     *
     * @param string $contemporary
     *
     * @return En
     */
    public function setContemporary($contemporary) {
        $this->contemporary = $contemporary;

        return $this;
    }

    /**
     * Get contemporary.
     *
     * @return string
     */
    public function getContemporary() {
        return $this->contemporary;
    }

    /**
     * Set shelfmark.
     *
     * @param string $shelfmark
     *
     * @return En
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
     * Set editions.
     *
     * @param string $editions
     *
     * @return En
     */
    public function setEditions($editions) {
        $this->editions = $editions;

        return $this;
    }

    /**
     * Get editions.
     *
     * @return string
     */
    public function getEditions() {
        return $this->editions;
    }

    /**
     * Set genre.
     *
     * @param string $genre
     *
     * @return En
     */
    public function setGenre($genre) {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre.
     *
     * @return string
     */
    public function getGenre() {
        return $this->genre;
    }

    /**
     * Set gender.
     *
     * @param string $gender
     *
     * @return En
     */
    public function setGender($gender) {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender.
     *
     * @return string
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * Set notes.
     *
     * @param string $notes
     *
     * @return En
     */
    public function setNotes($notes) {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes.
     *
     * @return string
     */
    public function getNotes() {
        return $this->notes;
    }
}
