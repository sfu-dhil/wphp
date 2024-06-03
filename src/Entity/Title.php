<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TitleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

#[ORM\Table(name: 'title')]
#[ORM\Index(name: 'title_title_ft', columns: ['title'], flags: ['fulltext'])]
#[ORM\Index(name: 'title_signedauthor_ft', columns: ['signed_author'], flags: ['fulltext'])]
#[ORM\Index(name: 'title_pseudonym_idx', columns: ['pseudonym'], flags: ['fulltext'])]
#[ORM\Index(name: 'title_imprint_idx', columns: ['imprint'], flags: ['fulltext'])]
#[ORM\Index(name: 'title_copyright_idx', columns: ['copyright'], flags: ['fulltext'])]
#[ORM\Index(name: 'title_colophon_idx', columns: ['colophon'], flags: ['fulltext'])]
#[ORM\Index(name: 'title_shelfmark_idx', columns: ['shelfmark'], flags: ['fulltext'])]
#[ORM\Index(name: 'title_notes_idx', columns: ['notes'], flags: ['fulltext'])]
#[ORM\Index(name: 'title_price_idx', columns: ['price_total'])]
#[ORM\Index(name: 'title_edition_idx', columns: ['edition'], flags: ['fulltext'])]
#[ORM\Entity(repositoryClass: TitleRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Title extends AbstractEntity {
    #[ORM\Column(name: 'title', type: 'text', nullable: false)]
    private string $title = '';

    #[ORM\Column(name: 'edition_number', type: 'integer', nullable: true)]
    private ?int $editionNumber = null;

    #[ORM\Column(name: 'signed_author', type: 'text', nullable: true)]
    private ?string $signedAuthor = null;

    #[ORM\Column(name: 'pseudonym', type: 'string', length: 255, nullable: true)]
    private ?string $pseudonym = null;

    #[ORM\Column(name: 'imprint', type: 'text', nullable: true)]
    private ?string $imprint = null;

    #[ORM\Column(name: 'copyright', type: 'text', nullable: true)]
    private ?string $copyright = null;

    #[ORM\Column(name: 'selfpublished', type: 'boolean', nullable: true)]
    private ?bool $selfpublished = null;

    #[ORM\Column(name: 'pubdate', type: 'string', length: 60, nullable: true)]
    private ?string $pubdate = null;

    #[ORM\Column(name: 'date_of_first_publication', type: 'string', length: 40, nullable: true)]
    private ?string $dateOfFirstPublication = null;

    #[ORM\Column(name: 'size_l', type: 'integer', nullable: true)]
    private ?int $sizeL = null;

    #[ORM\Column(name: 'size_w', type: 'integer', nullable: true)]
    private ?int $sizeW = null;

    #[ORM\Column(name: 'edition', type: 'string', length: 200, nullable: true)]
    private ?string $edition = null;

    #[ORM\Column(name: 'colophon', type: 'text', nullable: true)]
    private ?string $colophon;

    #[ORM\Column(name: 'volumes', type: 'integer', nullable: true)]
    private ?int $volumes;

    #[ORM\Column(name: 'pagination', type: 'string', length: 400, nullable: true)]
    private ?string $pagination;

    #[ORM\Column(name: 'price_total', type: 'integer', nullable: false)]
    private int $totalPrice = 0;

    #[ORM\Column(name: 'price_pound', type: 'integer', nullable: true)]
    private ?int $pricePound = null;

    #[ORM\Column(name: 'price_shilling', type: 'integer', nullable: true)]
    private ?int $priceShilling = null;

    #[ORM\Column(name: 'price_pence', type: 'decimal', precision: 9, scale: 1, nullable: true)]
    private ?string $pricePence = null;

    #[ORM\Column(name: 'other_price', type: 'decimal', precision: 7, scale: 2, nullable: true)]
    private ?string $otherPrice = null;

    #[ORM\Column(name: 'shelfmark', type: 'text', nullable: true)]
    private ?string $shelfmark = null;

    #[ORM\Column(name: 'checked', type: 'boolean', nullable: false)]
    private bool $checked = false;

    #[ORM\Column(name: 'finalcheck', type: 'boolean', nullable: false)]
    private bool $finalcheck = false;

    #[ORM\Column(name: 'finalattempt', type: 'boolean', nullable: false)]
    private bool $finalattempt = false;

    #[ORM\Column(name: 'edition_checked', type: 'boolean', nullable: false)]
    private bool $editionChecked = false;

    #[ORM\Column(name: 'notes', type: 'text', nullable: true)]
    private ?string $notes = null;

    #[ORM\JoinColumn(name: 'location_of_printing', referencedColumnName: 'geonameid')]
    #[ORM\ManyToOne(targetEntity: Geonames::class, inversedBy: 'titles')]
    private ?Geonames $locationOfPrinting = null;

    #[ORM\JoinColumn(name: 'format_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: Format::class, inversedBy: 'titles')]
    private ?Format $format = null;

    /**
     * @var Collection<int,Genre>
     */
    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'titles')]
    private array|Collection $genres;

    #[ORM\ManyToOne(targetEntity: Currency::class, inversedBy: 'titles')]
    private ?Currency $otherCurrency = null;

    /**
     * @var Collection<int,TitleRole>
     */
    #[ORM\OneToMany(targetEntity: TitleRole::class, mappedBy: 'title')]
    private array|Collection $titleRoles;

    /**
     * @var Collection<int,TitleFirmrole>
     */
    #[ORM\OneToMany(targetEntity: TitleFirmrole::class, mappedBy: 'title')]
    private array|Collection $titleFirmroles;

    /**
     * Title sources are where the bibliographic information comes from. It's
     * poorly named with respect to sourceTitles which records different
     * information.
     *
     * @var Collection<int,TitleSource>
     */
    #[ORM\OneToMany(targetEntity: TitleSource::class, mappedBy: 'title')]
    private array|Collection $titleSources;

    /**
     * @var Collection<int,Title>
     */
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'titlesRelated')]
    private array|Collection $relatedTitles;

    /**
     * @var Collection<int,Title>
     */
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'relatedTitles')]
    private array|Collection $titlesRelated;

    public function __construct() {
        parent::__construct();
        $this->totalPrice = 0;
        $this->genres = new ArrayCollection();
        $this->titleRoles = new ArrayCollection();
        $this->titleFirmroles = new ArrayCollection();
        $this->titleSources = new ArrayCollection();
        $this->titlesRelated = new ArrayCollection();
        $this->relatedTitles = new ArrayCollection();
    }

    public function __toString() : string {
        return $this->title;
    }

    public function getTitleId() : string {
        return "({$this->id}) {$this->title}";
    }

    public function setTitle(string $title) : self {
        $this->title = $title;

        return $this;
    }

    public function getTitle() : string {
        return $this->title;
    }

    public function getTrimmedTitle() : string {
        $trimmedTitle = preg_replace('/--+/', '', (string) $this->title ?? '');
        if (str_word_count($trimmedTitle) > 128) {
            $words = explode(' ', $trimmedTitle, 129);

            return implode(' ', array_slice($words, 0, 128));
        }

        return $trimmedTitle;
    }

    public function getFormId() : string {
        return "({$this->id}) {$this->title}";
    }

    public function setSignedAuthor(?string $signedAuthor) : self {
        $this->signedAuthor = $signedAuthor;

        return $this;
    }

    public function getSignedAuthor() : ?string {
        return $this->signedAuthor;
    }

    public function setPseudonym(?string $pseudonym) : self {
        $this->pseudonym = $pseudonym;

        return $this;
    }

    public function getPseudonym() : ?string {
        return $this->pseudonym;
    }

    public function setImprint(?string $imprint) : self {
        $this->imprint = $imprint;

        return $this;
    }

    public function getImprint() : ?string {
        return $this->imprint;
    }

    public function setCopyright(?string $copyright) : self {
        $this->copyright = $copyright;

        return $this;
    }

    public function getCopyright() : ?string {
        return $this->copyright;
    }

    public function setSelfpublished(?bool $selfpublished) : self {
        $this->selfpublished = $selfpublished;

        return $this;
    }

    public function getSelfpublished() : ?bool {
        return $this->selfpublished;
    }

    public function setPubdate(?string $pubdate) : self {
        $this->pubdate = $pubdate;

        return $this;
    }

    public function getPubdate() : ?string {
        return $this->pubdate;
    }

    public function setDateOfFirstPublication(?string $dateOfFirstPublication) : self {
        $this->dateOfFirstPublication = $dateOfFirstPublication;

        return $this;
    }

    public function getDateOfFirstPublication() : ?string {
        return $this->dateOfFirstPublication;
    }

    public function setSizeL(?int $sizeL) : self {
        $this->sizeL = $sizeL;

        return $this;
    }

    public function getSizeL() : ?int {
        return $this->sizeL;
    }

    public function setSizeW(?int $sizeW) : self {
        $this->sizeW = $sizeW;

        return $this;
    }

    public function getSizeW() : ?int {
        return $this->sizeW;
    }

    public function setEdition(?string $edition) : self {
        $this->edition = $edition;

        return $this;
    }

    public function getEdition() : ?string {
        return $this->edition;
    }

    public function setVolumes(?int $volumes) : self {
        $this->volumes = $volumes;

        return $this;
    }

    public function getVolumes() : ?int {
        return $this->volumes;
    }

    public function setPagination(?string $pagination) : self {
        $this->pagination = $pagination;

        return $this;
    }

    public function getPagination() : ?string {
        return $this->pagination;
    }

    public function setPricePound(null|bool|int|string $pricePound) : self {
        if (is_string($pricePound)) {
            $this->pricePound = is_numeric($pricePound) ? (int) $pricePound : null;
        } elseif (is_bool($pricePound)) {
            $this->pricePound = $pricePound ? 1 : 0;
        } else {
            $this->pricePound = $pricePound;
        }

        return $this;
    }

    public function getPricePound() : ?int {
        return $this->pricePound;
    }

    public function setPriceShilling(null|bool|int|string $priceShilling) : self {
        if (is_string($priceShilling)) {
            $this->priceShilling = is_numeric($priceShilling) ? (int) $priceShilling : null;
        } elseif (is_bool($priceShilling)) {
            $this->priceShilling = $priceShilling ? 1 : 0;
        } else {
            $this->priceShilling = $priceShilling;
        }

        return $this;
    }

    public function getPriceShilling() : ?int {
        return $this->priceShilling;
    }

    public function setPricePence(null|bool|int|string $pricePence) : self {
        if (is_string($pricePence)) {
            $this->pricePence = (string) (is_numeric($pricePence) ? (float) $pricePence : null);
        } elseif (is_bool($pricePence)) {
            $this->pricePence = (string) ($pricePence ? 1 : 0);
        } else {
            $this->pricePence = (string) $pricePence;
        }

        return $this;
    }

    public function getPricePence() : ?string {
        return $this->pricePence;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setTotalPrice() : void {
        $this->totalPrice = 0;

        if ($this->pricePound && is_int($this->pricePound)) {
            $this->totalPrice += $this->pricePound * 240;
        }
        if ($this->priceShilling && is_int($this->priceShilling)) {
            $this->totalPrice += $this->priceShilling * 12;
        }
        if ($this->pricePence && is_numeric($this->pricePence)) {
            $this->totalPrice += (int) $this->pricePence;
        }
    }

    public function getTotalPrice() : int {
        $this->setTotalPrice();

        return $this->totalPrice;
    }

    public function setShelfmark(?string $shelfmark) : self {
        $this->shelfmark = $shelfmark;

        return $this;
    }

    public function getShelfmark() : ?string {
        return $this->shelfmark;
    }

    public function setChecked(bool $checked) : self {
        $this->checked = $checked;

        return $this;
    }

    public function getChecked() : bool {
        return $this->checked;
    }

    public function setFinalcheck(bool $finalcheck) : self {
        $this->finalcheck = $finalcheck;

        return $this;
    }

    public function getFinalcheck() : bool {
        return $this->finalcheck;
    }

    public function setNotes(?string $notes) : self {
        $this->notes = $notes;

        return $this;
    }

    public function getNotes() : ?string {
        return $this->notes;
    }

    public function setLocationOfPrinting(?Geonames $locationOfPrinting = null) : self {
        $this->locationOfPrinting = $locationOfPrinting;

        return $this;
    }

    public function getLocationOfPrinting() : ?Geonames {
        return $this->locationOfPrinting;
    }

    public function setFormat(?Format $format = null) : self {
        $this->format = $format;

        return $this;
    }

    public function getFormat() : ?Format {
        return $this->format;
    }

    /**
     * @return Collection<int,Genre>
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

    public function hasTitleRole(Person $person, Role $role) : bool {
        foreach ($this->titleRoles as $tr) {
            if ($tr->getPerson() === $person && $tr->getRole() === $role) {
                return true;
            }
        }

        return false;
    }

    public function setEditionNumber(?int $editionNumber) : self {
        $this->editionNumber = $editionNumber;

        return $this;
    }

    public function getEditionNumber() : ?int {
        return $this->editionNumber;
    }

    public function setFinalattempt(bool $finalattempt) : self {
        $this->finalattempt = $finalattempt;

        return $this;
    }

    public function getFinalattempt() : bool {
        return $this->finalattempt;
    }

    public function setColophon(?string $colophon = null) : self {
        $this->colophon = $colophon;

        return $this;
    }

    public function getColophon() : ?string {
        return $this->colophon;
    }

    public function getOtherPrice() : ?string {
        return $this->otherPrice;
    }

    public function setOtherPrice(?string $otherPrice) : self {
        $this->otherPrice = (string) ((float) $otherPrice);

        return $this;
    }

    public function getOtherCurrency() : ?Currency {
        return $this->otherCurrency;
    }

    public function setOtherCurrency(?Currency $otherCurrency) : self {
        $this->otherCurrency = $otherCurrency;

        return $this;
    }

    public function getEditionChecked() : bool {
        return $this->editionChecked;
    }

    public function setEditionChecked(bool $editionChecked) : self {
        $this->editionChecked = $editionChecked;

        return $this;
    }

    public function getTitleRoles(?string $roleName = null) : Collection {
        if (null === $roleName) {
            return $this->titleRoles;
        }
        $roles = $this->titleRoles->filter(fn (TitleRole $titleRole) => $titleRole->getRole()->getName() === $roleName);

        return new ArrayCollection($roles->getValues());
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
     * @return Collection<int,TitleFirmrole>
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
     * @return Collection<int,TitleSource>
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
     * @return Collection<int,Title>
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
     * @return Collection<int,Title>
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
