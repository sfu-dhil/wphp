<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
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
 *     indexes={
 *         @ORM\Index(name="en_ft", columns={"author", "title"}, flags={"fulltext"})
 *     },
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="en_enid_uniq", columns={"en_id"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\EnRepository")
 */
class En {
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(name="en_id", type="string", length=20)
     */
    private string $enId;

    /**
     * @ORM\Column(name="year", type="integer")
     * @Assert\Range(min=0, max=2018)
     */
    private int $year;

    /**
     * @ORM\Column(name="author", type="string", length=160)
     */
    private string $author;

    /**
     * @ORM\Column(name="editor", type="string", length=160, nullable=true)
     */
    private ?string $editor = null;

    /**
     * @ORM\Column(name="translator", type="string", length=160, nullable=true)
     */
    private ?string  $translator = null;

    /**
     * @ORM\Column(name="title", type="text")
     */
    private string $title;

    /**
     * @ORM\Column(name="publish_place", type="string", length=100)
     */
    private string $publishPlace;

    /**
     * @ORM\Column(name="imprint_details", type="text")
     */
    private string $imprint;

    /**
     * @ORM\Column(name="pagination", type="string", length=100)
     */
    private string $pagination;

    /**
     * @ORM\Column(name="format", type="string", length=8)
     */
    private string $format;

    /**
     * @ORM\Column(name="price", type="string", length=100, nullable=true)
     */
    private ?string $price = null;

    /**
     * @ORM\Column(name="contemporary", type="string", length=200, nullable=true)
     */
    private ?string $contemporary = null;

    /**
     * @ORM\Column(name="shelfmark", type="string", length=200)
     */
    private string $shelfmark;

    /**
     * @ORM\Column(name="further_editions", type="text", nullable=true)
     */
    private ?string  $editions = null;

    /**
     * @ORM\Column(name="genre", type="string", length=64, nullable=true)
     */
    private ?string  $genre = null;

    /**
     * @ORM\Column(name="gender", type="string", length=1, nullable=true)
     */
    private ?string $gender = null;

    /**
     * @ORM\Column(name="notes", type="text")
     */
    private string $notes;

    public function __toString() : string {
        return $this->title;
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function getEnId() : ?string {
        return $this->enId;
    }

    public function setEnId(string $enId) : self {
        $this->enId = $enId;

        return $this;
    }

    public function getYear() : ?int {
        return $this->year;
    }

    public function setYear(int $year) : self {
        $this->year = $year;

        return $this;
    }

    public function getAuthor() : ?string {
        return $this->author;
    }

    public function setAuthor(string $author) : self {
        $this->author = $author;

        return $this;
    }

    public function getEditor() : ?string {
        return $this->editor;
    }

    public function setEditor(?string $editor) : self {
        $this->editor = $editor;

        return $this;
    }

    public function getTranslator() : ?string {
        return $this->translator;
    }

    public function setTranslator(?string $translator) : self {
        $this->translator = $translator;

        return $this;
    }

    public function getTitle() : ?string {
        return $this->title;
    }

    public function setTitle(string $title) : self {
        $this->title = $title;

        return $this;
    }

    public function getPublishPlace() : ?string {
        return $this->publishPlace;
    }

    public function setPublishPlace(string $publishPlace) : self {
        $this->publishPlace = $publishPlace;

        return $this;
    }

    public function getImprint() : ?string {
        return $this->imprint;
    }

    public function setImprint(string $imprint) : self {
        $this->imprint = $imprint;

        return $this;
    }

    public function getPagination() : ?string {
        return $this->pagination;
    }

    public function setPagination(string $pagination) : self {
        $this->pagination = $pagination;

        return $this;
    }

    public function getFormat() : ?string {
        return $this->format;
    }

    public function setFormat(string $format) : self {
        $this->format = $format;

        return $this;
    }

    public function getPrice() : ?string {
        return $this->price;
    }

    public function setPrice(?string $price) : self {
        $this->price = $price;

        return $this;
    }

    public function getContemporary() : ?string {
        return $this->contemporary;
    }

    public function setContemporary(?string $contemporary) : self {
        $this->contemporary = $contemporary;

        return $this;
    }

    public function getShelfmark() : ?string {
        return $this->shelfmark;
    }

    public function setShelfmark(string $shelfmark) : self {
        $this->shelfmark = $shelfmark;

        return $this;
    }

    public function getEditions() : ?string {
        return $this->editions;
    }

    public function setEditions(?string $editions) : self {
        $this->editions = $editions;

        return $this;
    }

    public function getGenre() : ?string {
        return $this->genre;
    }

    public function setGenre(?string $genre) : self {
        $this->genre = $genre;

        return $this;
    }

    public function getGender() : ?string {
        return $this->gender;
    }

    public function setGender(?string $gender) : self {
        $this->gender = $gender;

        return $this;
    }

    public function getNotes() : ?string {
        return $this->notes;
    }

    public function setNotes(string $notes) : self {
        $this->notes = $notes;

        return $this;
    }
}
