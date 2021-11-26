<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Title.
 *
 * @ORM\Table(name="title",
 *     indexes={
 *         @ORM\Index(name="title_title_ft", columns={"title"}, flags={"fulltext"}),
 *         @ORM\Index(name="title_signedauthor_ft", columns={"signed_author"}, flags={"fulltext"}),
 *         @ORM\Index(name="title_pseudonym_idx", columns={"pseudonym"}, flags={"fulltext"}),
 *         @ORM\Index(name="title_imprint_idx", columns={"imprint"}, flags={"fulltext"}),
 *         @ORM\Index(name="title_copyright_idx", columns={"copyright"}, flags={"fulltext"}),
 *         @ORM\Index(name="title_colophon_idx", columns={"colophon"}, flags={"fulltext"}),
 *         @ORM\Index(name="title_shelfmark_idx", columns={"shelfmark"}, flags={"fulltext"}),
 *         @ORM\Index(name="title_notes_idx", columns={"notes"}, flags={"fulltext"}),
 *         @ORM\Index(name="title_price_idx", columns={"price_total"}),
 *         @ORM\Index(name="title_edition_idx", columns={"edition"}, flags={"fulltext"})
 *     })
 *     @ORM\Entity(repositoryClass="App\Repository\TitleRepository")
 *     @ORM\HasLifecycleCallbacks
 */
class Title {
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\Column(name="title", type="text", nullable=false)
     */
    private string $title;

    /**
     * @ORM\Column(name="edition_number", type="integer", nullable=true)
     */
    private ?int $editionNumber = null;

    /**
     * @ORM\Column(name="signed_author", type="text", nullable=true)
     */
    private ?string $signedAuthor = null;

    /**
     * @ORM\Column(name="pseudonym", type="string", length=255, nullable=true)
     */
    private ?string $pseudonym = null;

    /**
     * @ORM\Column(name="imprint", type="text", nullable=true)
     */
    private ?string $imprint = null;

    /**
     * @ORM\Column(name="copyright", type="text", nullable=true)
     */
    private ?string $copyright = null;

    /**
     * @ORM\Column(name="selfpublished", type="boolean", nullable=true)
     */
    private $selfpublished = false;

    /**
     * @ORM\Column(name="pubdate", type="string", length=40, nullable=true)
     */
    private ?string $pubdate = null;

    /**
     * @ORM\Column(name="date_of_first_publication", type="string", length=40, nullable=true)
     */
    private ?string $dateOfFirstPublication = null;

    /**
     * @ORM\Column(name="size_l", type="integer", nullable=true)
     */
    private ?int $sizeL = null;

    /**
     * @ORM\Column(name="size_w", type="integer", nullable=true)
     */
    private ?int $sizeW = null;

    /**
     * @ORM\Column(name="edition", type="string", length=200, nullable=true)
     */
    private ?string $edition = null;

    /**
     * @ORM\Column(name="colophon", type="text", nullable=true)
     */
    private ?string $colophon = null;

    /**
     * @ORM\Column(name="volumes", type="integer", nullable=true)
     */
    private ?int $volumes = null;

    /**
     * @ORM\Column(name="pagination", type="string", length=200, nullable=true)
     */
    private ?string $pagination = null;

    /**
     * @ORM\Column(name="price_total", type="integer", nullable=false)
     */
    private int $totalPrice;

    /**
     * @ORM\Column(name="price_pound", type="integer", nullable=true)
     */
    private ?int $pricePound = null;

    /**
     * @ORM\Column(name="price_shilling", type="integer", nullable=true)
     */
    private ?int $priceShilling = null;

    /**
     * @ORM\Column(name="price_pence", type="decimal", precision=9, scale=1, nullable=true)
     */
    private ?float $pricePence = null;

    /**
     * @ORM\Column(name="other_price", type="decimal", precision=7, scale=2, nullable=true)
     */
    private ?float $otherPrice = null;

    /**
     * @ORM\Column(name="shelfmark", type="text", nullable=true)
     */
    private ?string $shelfmark = null;

    /**
     * @ORM\Column(name="checked", type="boolean", nullable=false)
     */
    private bool $checked = false;

    /**
     * @ORM\Column(name="finalcheck", type="boolean", nullable=false)
     */
    private bool $finalcheck = false;

    /**
     * @ORM\Column(name="finalattempt", type="boolean", nullable=false)
     */
    private bool $finalattempt = false;

    /**
     * @ORM\Column(name="edition_checked", type="boolean", nullable=false)
     */
    private bool $editionChecked = false;

    /**
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private ?string $notes = null;

    /**
     * @ORM\ManyToOne(targetEntity="Geonames", inversedBy="titles")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="location_of_printing", referencedColumnName="geonameid")
     * })
     */
    private ?Geonames $locationOfPrinting = null;

    /**
     * @ORM\ManyToOne(targetEntity="Format", inversedBy="titles")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="format_id", referencedColumnName="id")
     * })
     */
    private ?Format $format = null;

    /**
     * @var Collection|Genre[]
     * @ORM\ManyToMany(targetEntity="Genre", inversedBy="titles")
     */
    private $genres;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency", inversedBy="titles")
     */
    private ?Currency $otherCurrency = null;

    /**
     * @var Collection|TitleRole[]
     * @ORM\OneToMany(targetEntity="TitleRole", mappedBy="title")
     */
    private $titleRoles;

    /**
     * @var Collection|TitleFirmrole[]
     * @ORM\OneToMany(targetEntity="TitleFirmrole", mappedBy="title")
     */
    private $titleFirmroles;

    /**
     * Title sources are where the bibliographic information comes from. It's
     * poorly named with respect to sourceTitles which records different
     * information.
     *
     * @var Collection|TitleSource[]
     * @ORM\OneToMany(targetEntity="TitleSource", mappedBy="title")
     */
    private $titleSources;

    /**
     * @var Collection|Title[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Title", inversedBy="titlesRelated")
     */
    private $relatedTitles;

    /**
     * @var Collection|Title[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Title", mappedBy="relatedTitles")
     */
    private $titlesRelated;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->totalPrice = 0;
        $this->genres = new ArrayCollection();
        $this->titleRoles = new ArrayCollection();
        $this->titleFirmroles = new ArrayCollection();
        $this->titleSources = new ArrayCollection();
        $this->titlesRelated = new ArrayCollection();
        $this->relatedTitles = new ArrayCollection();
    }

    /**
     * Return the title's title.
     */
    public function __toString() : string {
        return $this->title;
    }

    public function getTitleId() : string {
        return "({$this->id}) {$this->title}";
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function getTotalPrice() : int {
        $this->totalPrice = 0;

        if ($this->pricePound && is_numeric($this->pricePound)) {
            $this->totalPrice += $this->pricePound * 240;
        }
        if ($this->priceShilling && is_numeric($this->priceShilling)) {
            $this->totalPrice += $this->priceShilling * 12;
        }
        if ($this->pricePence && is_numeric($this->pricePence)) {
            $this->totalPrice += (int) round($this->pricePence);
        }

        return $this->totalPrice;
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function getTitle() : ?string {
        return $this->title;
    }

    public function setTitle(string $title) : self {
        $this->title = $title;

        return $this;
    }

    public function getEditionNumber() : ?int {
        return $this->editionNumber;
    }

    public function setEditionNumber(?int $editionNumber) : self {
        $this->editionNumber = $editionNumber;

        return $this;
    }

    public function getSignedAuthor() : ?string {
        return $this->signedAuthor;
    }

    public function setSignedAuthor(?string $signedAuthor) : self {
        $this->signedAuthor = $signedAuthor;

        return $this;
    }

    public function getPseudonym() : ?string {
        return $this->pseudonym;
    }

    public function setPseudonym(?string $pseudonym) : self {
        $this->pseudonym = $pseudonym;

        return $this;
    }

    public function getImprint() : ?string {
        return $this->imprint;
    }

    public function setImprint(?string $imprint) : self {
        $this->imprint = $imprint;

        return $this;
    }

    public function getCopyright() : ?string {
        return $this->copyright;
    }

    public function setCopyright(?string $copyright) : self {
        $this->copyright = $copyright;

        return $this;
    }

    public function getSelfpublished() : ?bool {
        return $this->selfpublished;
    }

    public function setSelfpublished(?bool $selfpublished) : self {
        $this->selfpublished = $selfpublished;

        return $this;
    }

    public function getPubdate() : ?string {
        return $this->pubdate;
    }

    public function setPubdate(?string $pubdate) : self {
        $this->pubdate = $pubdate;

        return $this;
    }

    public function getDateOfFirstPublication() : ?string {
        return $this->dateOfFirstPublication;
    }

    public function setDateOfFirstPublication(?string $dateOfFirstPublication) : self {
        $this->dateOfFirstPublication = $dateOfFirstPublication;

        return $this;
    }

    public function getSizeL() : ?int {
        return $this->sizeL;
    }

    public function setSizeL(?int $sizeL) : self {
        $this->sizeL = $sizeL;

        return $this;
    }

    public function getSizeW() : ?int {
        return $this->sizeW;
    }

    public function setSizeW(?int $sizeW) : self {
        $this->sizeW = $sizeW;

        return $this;
    }

    public function getEdition() : ?string {
        return $this->edition;
    }

    public function setEdition(?string $edition) : self {
        $this->edition = $edition;

        return $this;
    }

    public function getColophon() : ?string {
        return $this->colophon;
    }

    public function setColophon(?string $colophon) : self {
        $this->colophon = $colophon;

        return $this;
    }

    public function getVolumes() : ?int {
        return $this->volumes;
    }

    public function setVolumes(?int $volumes) : self {
        $this->volumes = $volumes;

        return $this;
    }

    public function getPagination() : ?string {
        return $this->pagination;
    }

    public function setPagination(?string $pagination) : self {
        $this->pagination = $pagination;

        return $this;
    }

    public function setTotalPrice(int $totalPrice) : self {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getPricePound() : ?int {
        return $this->pricePound;
    }

    public function setPricePound(?int $pricePound) : self {
        $this->pricePound = $pricePound;

        return $this;
    }

    public function getPriceShilling() : ?int {
        return $this->priceShilling;
    }

    public function setPriceShilling(?int $priceShilling) : self {
        $this->priceShilling = $priceShilling;

        return $this;
    }

    public function getPricePence() : ?float {
        return $this->pricePence;
    }

    public function setPricePence(?float $pricePence) : self {
        $this->pricePence = $pricePence;

        return $this;
    }

    public function getOtherPrice() : ?float {
        return $this->otherPrice;
    }

    public function setOtherPrice(?float $otherPrice) : self {
        $this->otherPrice = $otherPrice;

        return $this;
    }

    public function getShelfmark() : ?string {
        return $this->shelfmark;
    }

    public function setShelfmark(?string $shelfmark) : self {
        $this->shelfmark = $shelfmark;

        return $this;
    }

    public function getChecked() : ?bool {
        return $this->checked;
    }

    public function setChecked(bool $checked) : self {
        $this->checked = $checked;

        return $this;
    }

    public function getFinalcheck() : ?bool {
        return $this->finalcheck;
    }

    public function setFinalcheck(bool $finalcheck) : self {
        $this->finalcheck = $finalcheck;

        return $this;
    }

    public function getFinalattempt() : ?bool {
        return $this->finalattempt;
    }

    public function setFinalattempt(bool $finalattempt) : self {
        $this->finalattempt = $finalattempt;

        return $this;
    }

    public function getEditionChecked() : ?bool {
        return $this->editionChecked;
    }

    public function setEditionChecked(bool $editionChecked) : self {
        $this->editionChecked = $editionChecked;

        return $this;
    }

    public function getNotes() : ?string {
        return $this->notes;
    }

    public function setNotes(?string $notes) : self {
        $this->notes = $notes;

        return $this;
    }

    public function getLocationOfPrinting() : ?Geonames {
        return $this->locationOfPrinting;
    }

    public function setLocationOfPrinting(?Geonames $locationOfPrinting) : self {
        $this->locationOfPrinting = $locationOfPrinting;

        return $this;
    }

    public function getFormat() : ?Format {
        return $this->format;
    }

    public function setFormat(?Format $format) : self {
        $this->format = $format;

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres() : Collection {
        return $this->genres;
    }

    public function addGenre(Genre $genre) : self {
        if ( ! $this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre) : self {
        $this->genres->removeElement($genre);

        return $this;
    }

    public function getOtherCurrency() : ?Currency {
        return $this->otherCurrency;
    }

    public function setOtherCurrency(?Currency $otherCurrency) : self {
        $this->otherCurrency = $otherCurrency;

        return $this;
    }

    /**
     * @return Collection|TitleRole[]
     */
    public function getTitleRoles() : Collection {
        return $this->titleRoles;
    }

    public function addTitleRole(TitleRole $titleRole) : self {
        if ( ! $this->titleRoles->contains($titleRole)) {
            $this->titleRoles[] = $titleRole;
            $titleRole->setTitle($this);
        }

        return $this;
    }

    public function removeTitleRole(TitleRole $titleRole) : self {
        if ($this->titleRoles->removeElement($titleRole)) {
            // set the owning side to null (unless already changed)
            if ($titleRole->getTitle() === $this) {
                $titleRole->setTitle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TitleFirmrole[]
     */
    public function getTitleFirmroles() : Collection {
        return $this->titleFirmroles;
    }

    public function addTitleFirmrole(TitleFirmrole $titleFirmrole) : self {
        if ( ! $this->titleFirmroles->contains($titleFirmrole)) {
            $this->titleFirmroles[] = $titleFirmrole;
            $titleFirmrole->setTitle($this);
        }

        return $this;
    }

    public function removeTitleFirmrole(TitleFirmrole $titleFirmrole) : self {
        if ($this->titleFirmroles->removeElement($titleFirmrole)) {
            // set the owning side to null (unless already changed)
            if ($titleFirmrole->getTitle() === $this) {
                $titleFirmrole->setTitle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TitleSource[]
     */
    public function getTitleSources() : Collection {
        return $this->titleSources;
    }

    public function addTitleSource(TitleSource $titleSource) : self {
        if ( ! $this->titleSources->contains($titleSource)) {
            $this->titleSources[] = $titleSource;
            $titleSource->setTitle($this);
        }

        return $this;
    }

    public function removeTitleSource(TitleSource $titleSource) : self {
        if ($this->titleSources->removeElement($titleSource)) {
            // set the owning side to null (unless already changed)
            if ($titleSource->getTitle() === $this) {
                $titleSource->setTitle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Title[]
     */
    public function getRelatedTitles() : Collection {
        return $this->relatedTitles;
    }

    public function addRelatedTitle(self $relatedTitle) : self {
        if ( ! $this->relatedTitles->contains($relatedTitle)) {
            $this->relatedTitles[] = $relatedTitle;
        }

        return $this;
    }

    public function removeRelatedTitle(self $relatedTitle) : self {
        $this->relatedTitles->removeElement($relatedTitle);

        return $this;
    }

    /**
     * @return Collection|Title[]
     */
    public function getTitlesRelated() : Collection {
        return $this->titlesRelated;
    }

    public function addTitlesRelated(self $titlesRelated) : self {
        if ( ! $this->titlesRelated->contains($titlesRelated)) {
            $this->titlesRelated[] = $titlesRelated;
            $titlesRelated->addRelatedTitle($this);
        }

        return $this;
    }

    public function removeTitlesRelated(self $titlesRelated) : self {
        if ($this->titlesRelated->removeElement($titlesRelated)) {
            $titlesRelated->removeRelatedTitle($this);
        }

        return $this;
    }
}
