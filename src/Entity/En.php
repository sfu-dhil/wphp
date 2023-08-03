<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\EnRepository;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * English Novel bibliographic entry.
 */
#[ORM\Table(name: 'en')]
#[ORM\Index(name: 'en_ft', columns: ['author', 'title'], flags: ['fulltext'])]
#[ORM\UniqueConstraint(name: 'en_enid_uniq', columns: ['en_id'])]
#[ORM\Entity(repositoryClass: EnRepository::class)]
class En implements Stringable {
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(name: 'en_id', type: 'string', length: 20)]
    private ?string $enId = null;

    #[ORM\Column(name: 'year', type: 'integer')]
    #[Assert\Range(min: 0, max: 2018)]
    private ?int $year = null;

    #[ORM\Column(name: 'author', type: 'string', length: 160)]
    private ?string $author = null;

    #[ORM\Column(name: 'editor', type: 'string', length: 160, nullable: true)]
    private ?string $editor = null;

    #[ORM\Column(name: 'translator', type: 'string', length: 160, nullable: true)]
    private ?string $translator = null;

    #[ORM\Column(name: 'title', type: 'text')]
    private ?string $title = null;

    #[ORM\Column(name: 'publish_place', type: 'string', length: 100)]
    private ?string $publishPlace = null;

    #[ORM\Column(name: 'imprint_details', type: 'text')]
    private ?string $imprint = null;

    #[ORM\Column(name: 'pagination', type: 'string', length: 100)]
    private ?string $pagination = null;

    #[ORM\Column(name: 'format', type: 'string', length: 8)]
    private ?string $format = null;

    #[ORM\Column(name: 'price', type: 'string', length: 100, nullable: true)]
    private ?string $price = null;

    #[ORM\Column(name: 'contemporary', type: 'string', length: 200, nullable: true)]
    private ?string $contemporary = null;

    #[ORM\Column(name: 'shelfmark', type: 'string', length: 200)]
    private ?string $shelfmark = null;

    #[ORM\Column(name: 'further_editions', type: 'text', nullable: true)]
    private ?string $editions = null;

    #[ORM\Column(name: 'genre', type: 'string', length: 64, nullable: true)]
    private ?string $genre = null;

    #[ORM\Column(name: 'gender', type: 'string', length: 1, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(name: 'notes', type: 'text')]
    private ?string $notes = null;

    public function __toString() : string {
        return $this->title;
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function setEnId(?string $enId) : self {
        $this->enId = $enId;

        return $this;
    }

    public function getEnId() : ?string {
        return $this->enId;
    }

    public function setYear(?int $year) : self {
        $this->year = $year;

        return $this;
    }

    public function getYear() : ?int {
        return $this->year;
    }

    public function setAuthor(?string $author) : self {
        $this->author = $author;

        return $this;
    }

    public function getAuthor() : ?string {
        return $this->author;
    }

    public function setEditor(?string $editor) : self {
        $this->editor = $editor;

        return $this;
    }

    public function getEditor() : ?string {
        return $this->editor;
    }

    public function setTranslator(?string $translator) : self {
        $this->translator = $translator;

        return $this;
    }

    public function getTranslator() : ?string {
        return $this->translator;
    }

    public function setTitle(?string $title) : self {
        $this->title = $title;

        return $this;
    }

    public function getTitle() : ?string {
        return $this->title;
    }

    public function setPublishPlace(?string $publishPlace) : self {
        $this->publishPlace = $publishPlace;

        return $this;
    }

    public function getPublishPlace() : ?string {
        return $this->publishPlace;
    }

    public function setImprint(?string $imprint) : self {
        $this->imprint = $imprint;

        return $this;
    }

    public function getImprint() : ?string {
        return $this->imprint;
    }

    public function setPagination(?string $pagination) : self {
        $this->pagination = $pagination;

        return $this;
    }

    public function getPagination() : ?string {
        return $this->pagination;
    }

    public function setFormat(?string $format) : self {
        $this->format = $format;

        return $this;
    }

    public function getFormat() : ?string {
        return $this->format;
    }

    public function setPrice(?string $price) : self {
        $this->price = $price;

        return $this;
    }

    public function getPrice() : ?string {
        return $this->price;
    }

    public function setContemporary(?string $contemporary) : self {
        $this->contemporary = $contemporary;

        return $this;
    }

    public function getContemporary() : ?string {
        return $this->contemporary;
    }

    public function setShelfmark(?string $shelfmark) : self {
        $this->shelfmark = $shelfmark;

        return $this;
    }

    public function getShelfmark() : ?string {
        return $this->shelfmark;
    }

    public function setEditions(?string $editions) : self {
        $this->editions = $editions;

        return $this;
    }

    public function getEditions() : ?string {
        return $this->editions;
    }

    public function setGenre(?string $genre) : self {
        $this->genre = $genre;

        return $this;
    }

    public function getGenre() : ?string {
        return $this->genre;
    }

    public function setGender(?string $gender) : self {
        $this->gender = $gender;

        return $this;
    }

    public function getGender() : ?string {
        return $this->gender;
    }

    public function setNotes(?string $notes) : self {
        $this->notes = $notes;

        return $this;
    }

    public function getNotes() : ?string {
        return $this->notes;
    }
}
