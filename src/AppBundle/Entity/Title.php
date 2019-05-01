<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Title
 *
 * @ORM\Table(name="title",
 *  indexes={
 *      @ORM\Index(name="title_title_ft", columns={"title"}, flags={"fulltext"}),
 *      @ORM\Index(name="title_signedauthor_ft", columns={"signed_author"}, flags={"fulltext"}),
 *      @ORM\Index(name="title_pseudonym_idx", columns={"pseudonym"}, flags={"fulltext"}),
 *      @ORM\Index(name="title_imprint_idx", columns={"imprint"}, flags={"fulltext"}),
 *      @ORM\Index(name="title_colophon_idx", columns={"colophon"}, flags={"fulltext"}),
 *      @ORM\Index(name="title_shelfmark_idx", columns={"shelfmark"}, flags={"fulltext"}),
 *      @ORM\Index(name="title_notes_idx", columns={"notes"}, flags={"fulltext"}),
 *      @ORM\Index(name="title_price_idx", columns={"price_total"})
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TitleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Title
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", nullable=false)
     */
    private $title;

    /**
     * @var int
     * @ORM\Column(name="edition_number", type="integer", nullable=true)
     */
    private $editionNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="signed_author", type="text", nullable=true)
     */
    private $signedAuthor;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudonym", type="string", length=255, nullable=true)
     */
    private $pseudonym;

    /**
     * @var string
     *
     * @ORM\Column(name="imprint", type="text", nullable=true)
     */
    private $imprint;

    /**
     * @var boolean
     *
     * @ORM\Column(name="selfpublished", type="boolean", nullable=true)
     */
    private $selfpublished = false;

    /**
     * @var string
     *
     * @ORM\Column(name="pubdate", type="string", length=40, nullable=true)
     */
    private $pubdate;

    /**
     * @var string
     *
     * @ORM\Column(name="date_of_first_publication", type="string", length=40, nullable=true)
     */
    private $dateOfFirstPublication;

    /**
     * @var boolean
     *
     * @ORM\Column(name="size_l", type="integer", nullable=true)
     */
    private $sizeL;

    /**
     * @var boolean
     *
     * @ORM\Column(name="size_w", type="integer", nullable=true)
     */
    private $sizeW;

    /**
     * @var string
     *
     * @ORM\Column(name="edition", type="string", length=200, nullable=true)
     */
    private $edition;

    /**
     * @var string
     *
     * @ORM\Column(name="colophon", type="string", length=200, nullable=true)
     */
    private $colophon;

    /**
     * @var boolean
     *
     * @ORM\Column(name="volumes", type="integer", nullable=true)
     */
    private $volumes;

    /**
     * @var string
     *
     * @ORM\Column(name="pagination", type="string", length=100, nullable=true)
     */
    private $pagination;

    /**
     * @var integer
     *
     * @ORM\Column(name="price_total", type="integer", nullable=false)
     */
    private $totalPrice;

    /**
     * @var integer
     *
     * @ORM\Column(name="price_pound", type="integer", nullable=true)
     */
    private $pricePound;

    /**
     * @var integer
     *
     * @ORM\Column(name="price_shilling", type="integer", nullable=true)
     */
    private $priceShilling;

    /**
     * @var string
     *
     * @ORM\Column(name="price_pence", type="decimal", precision=9, scale=1, nullable=true)
     */
    private $pricePence;

    /**
     * @var string
     *
     * @ORM\Column(name="shelfmark", type="text", nullable=true)
     */
    private $shelfmark;

    /**
     * @var boolean
     *
     * @ORM\Column(name="checked", type="boolean", nullable=false)
     */
    private $checked = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="finalcheck", type="boolean", nullable=false)
     */
    private $finalcheck = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="finalattempt", type="boolean", nullable=false)
     */
    private $finalattempt = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;

    /**
     * @var Geonames
     *
     * @ORM\ManyToOne(targetEntity="Geonames", inversedBy="titles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="location_of_printing", referencedColumnName="geonameid")
     * })
     */
    private $locationOfPrinting;

    /**
     * @var Format
     *
     * @ORM\ManyToOne(targetEntity="Format", inversedBy="titles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="format_id", referencedColumnName="id")
     * })
     */
    private $format;

    /**
     * @var Genre
     *
     * @ORM\ManyToOne(targetEntity="Genre", inversedBy="titles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="genre_id", referencedColumnName="id")
     * })
     */
    private $genre;

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
     * @var Collection|TitleRole[]
     * @ORM\OneToMany(targetEntity="TitleSource", mappedBy="title")
     */
    private $titleSources;

    /**
     * Constructor
     */
    public function __construct() {
        $this->totalPrice = 0;
        $this->titleRoles = new ArrayCollection();
        $this->titleFirmroles = new ArrayCollection();
        $this->titleSources = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Title
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set signedAuthor
     *
     * @param string $signedAuthor
     *
     * @return Title
     */
    public function setSignedAuthor($signedAuthor) {
        $this->signedAuthor = $signedAuthor;

        return $this;
    }

    /**
     * Get signedAuthor
     *
     * @return string
     */
    public function getSignedAuthor() {
        return $this->signedAuthor;
    }

    /**
     * Set surrogate
     *
     * @param string $surrogate
     *
     * @return Title
     */
    public function setSurrogate($surrogate) {
        $this->surrogate = $surrogate;

        return $this;
    }

    /**
     * Get surrogate
     *
     * @return string
     */
    public function getSurrogate() {
        return $this->surrogate;
    }

    /**
     * Set pseudonym
     *
     * @param string $pseudonym
     *
     * @return Title
     */
    public function setPseudonym($pseudonym) {
        $this->pseudonym = $pseudonym;

        return $this;
    }

    /**
     * Get pseudonym
     *
     * @return string
     */
    public function getPseudonym() {
        return $this->pseudonym;
    }

    /**
     * Set imprint
     *
     * @param string $imprint
     *
     * @return Title
     */
    public function setImprint($imprint) {
        $this->imprint = $imprint;

        return $this;
    }

    /**
     * Get imprint
     *
     * @return string
     */
    public function getImprint() {
        return $this->imprint;
    }

    /**
     * Set selfpublished
     *
     * @param boolean $selfpublished
     *
     * @return Title
     */
    public function setSelfpublished($selfpublished) {
        $this->selfpublished = $selfpublished;

        return $this;
    }

    /**
     * Get selfpublished
     *
     * @return boolean
     */
    public function getSelfpublished() {
        return $this->selfpublished;
    }

    /**
     * Set pubdate
     *
     * @param string $pubdate
     *
     * @return Title
     */
    public function setPubdate($pubdate) {
        $this->pubdate = $pubdate;

        return $this;
    }

    /**
     * Get pubdate
     *
     * @return string
     */
    public function getPubdate() {
        return $this->pubdate;
    }

    /**
     * Set dateOfFirstPublication
     *
     * @param string $dateOfFirstPublication
     *
     * @return Title
     */
    public function setDateOfFirstPublication($dateOfFirstPublication) {
        $this->dateOfFirstPublication = $dateOfFirstPublication;

        return $this;
    }

    /**
     * Get dateOfFirstPublication
     *
     * @return string
     */
    public function getDateOfFirstPublication() {
        return $this->dateOfFirstPublication;
    }

    /**
     * Set sizeL
     *
     * @param boolean $sizeL
     *
     * @return Title
     */
    public function setSizeL($sizeL) {
        $this->sizeL = $sizeL;

        return $this;
    }

    /**
     * Get sizeL
     *
     * @return boolean
     */
    public function getSizeL() {
        return $this->sizeL;
    }

    /**
     * Set sizeW
     *
     * @param boolean $sizeW
     *
     * @return Title
     */
    public function setSizeW($sizeW) {
        $this->sizeW = $sizeW;

        return $this;
    }

    /**
     * Get sizeW
     *
     * @return boolean
     */
    public function getSizeW() {
        return $this->sizeW;
    }

    /**
     * Set edition
     *
     * @param string $edition
     *
     * @return Title
     */
    public function setEdition($edition) {
        $this->edition = $edition;

        return $this;
    }

    /**
     * Get edition
     *
     * @return string
     */
    public function getEdition() {
        return $this->edition;
    }

    /**
     * Set volumes
     *
     * @param boolean $volumes
     *
     * @return Title
     */
    public function setVolumes($volumes) {
        $this->volumes = $volumes;

        return $this;
    }

    /**
     * Get volumes
     *
     * @return boolean
     */
    public function getVolumes() {
        return $this->volumes;
    }

    /**
     * Set pagination
     *
     * @param string $pagination
     *
     * @return Title
     */
    public function setPagination($pagination) {
        $this->pagination = $pagination;

        return $this;
    }

    /**
     * Get pagination
     *
     * @return string
     */
    public function getPagination() {
        return $this->pagination;
    }

    /**
     * Set pricePound
     *
     * @param integer $pricePound
     *
     * @return Title
     */
    public function setPricePound($pricePound) {
        $this->pricePound = $pricePound;

        return $this;
    }

    /**
     * Get pricePound
     *
     * @return integer
     */
    public function getPricePound() {
        return $this->pricePound;
    }

    /**
     * Set priceShilling
     *
     * @param integer $priceShilling
     *
     * @return Title
     */
    public function setPriceShilling($priceShilling) {
        $this->priceShilling = $priceShilling;

        return $this;
    }

    /**
     * Get priceShilling
     *
     * @return integer
     */
    public function getPriceShilling() {
        return $this->priceShilling;
    }

    /**
     * Set pricePence
     *
     * @param string $pricePence
     *
     * @return Title
     */
    public function setPricePence($pricePence) {
        $this->pricePence = $pricePence;

        return $this;
    }

    /**
     * Get pricePence
     *
     * @return string
     */
    public function getPricePence() {
        return $this->pricePence;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setTotalPrice() {
        $this->totalPrice = 0;

        if ($this->pricePound && is_int($this->pricePound)) {
            $this->totalPrice += $this->pricePound * 240;
        }
        if ($this->priceShilling && is_int($this->priceShilling)) {
            $this->totalPrice += $this->priceShilling * 12;
        }
        if ($this->pricePence && is_int($this->pricePence)) {
            $this->totalPrice += $this->pricePence;
        }
    }

    /**
     * Get the totalPrice in pence.
     *
     * @return integer
     */
    public function getTotalPrice() {
        $this->setTotalPrice();
        return $this->totalPrice;
    }

    /**
     * Set shelfmark
     *
     * @param string $shelfmark
     *
     * @return Title
     */
    public function setShelfmark($shelfmark) {
        $this->shelfmark = $shelfmark;

        return $this;
    }

    /**
     * Get shelfmark
     *
     * @return string
     */
    public function getShelfmark() {
        return $this->shelfmark;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     *
     * @return Title
     */
    public function setChecked($checked) {
        $this->checked = $checked;

        return $this;
    }

    /**
     * Get checked
     *
     * @return boolean
     */
    public function getChecked() {
        return $this->checked;
    }

    /**
     * Set finalcheck
     *
     * @param boolean $finalcheck
     *
     * @return Title
     */
    public function setFinalcheck($finalcheck) {
        $this->finalcheck = $finalcheck;

        return $this;
    }

    /**
     * Get finalcheck
     *
     * @return boolean
     */
    public function getFinalcheck() {
        return $this->finalcheck;
    }

    /**
     * Set notes
     *
     * @param string $notes
     *
     * @return Title
     */
    public function setNotes($notes) {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes() {
        return $this->notes;
    }

    /**
     * Set locationOfPrinting
     *
     * @param Geonames $locationOfPrinting
     *
     * @return Title
     */
    public function setLocationOfPrinting(Geonames $locationOfPrinting = null) {
        $this->locationOfPrinting = $locationOfPrinting;

        return $this;
    }

    /**
     * Get locationOfPrinting
     *
     * @return Geonames
     */
    public function getLocationOfPrinting() {
        return $this->locationOfPrinting;
    }

    /**
     * Set format
     *
     * @param Format $format
     *
     * @return Title
     */
    public function setFormat(Format $format = null) {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format
     *
     * @return Format
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     * Set genre
     *
     * @param Genre $genre
     *
     * @return Title
     */
    public function setGenre(Genre $genre = null) {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return Genre
     */
    public function getGenre() {
        return $this->genre;
    }

    /**
     * Add titleRole
     *
     * @param TitleRole $titleRole
     *
     * @return Title
     */
    public function addTitleRole(TitleRole $titleRole) {
        $this->titleRoles[] = $titleRole;

        return $this;
    }

    /**
     * Remove titleRole
     *
     * @param TitleRole $titleRole
     */
    public function removeTitleRole(TitleRole $titleRole) {
        $this->titleRoles->removeElement($titleRole);
    }

    /**
     * Get titleRoles, optionally filtered by role name.
     *
     * @param string $roleName
     *
     * @return Collection|TitleRole[]
     */
    public function getTitleRoles($roleName = null) {
        if ($roleName === null) {
            return $this->titleRoles;
        }
        $roles = $this->titleRoles->filter(function(TitleRole $titleRole) use ($roleName) {
                    return $titleRole->getRole()->getName() === $roleName;
        });
        return $roles->getValues();
    }

    /**
     * Add titleFirmrole
     *
     * @param TitleFirmrole $titleFirmrole
     *
     * @return Title
     */
    public function addTitleFirmrole(TitleFirmrole $titleFirmrole) {
        $this->titleFirmroles[] = $titleFirmrole;

        return $this;
    }

    /**
     * Remove titleFirmrole
     *
     * @param TitleFirmrole $titleFirmrole
     */
    public function removeTitleFirmrole(TitleFirmrole $titleFirmrole) {
        $titleFirmrole->setTitle(null);
        $titleFirmrole->setFirm(null);
        $titleFirmrole->setFirmrole(null);
        $this->titleFirmroles->removeElement($titleFirmrole);
    }

    /**
     * Get titleFirmroles, optionally filtered by role name.
     *
     * @param string $roleName
     *
     * @return Collection
     */
    public function getTitleFirmroles($roleName = null) {
        if ($roleName === null) {
            return $this->titleFirmroles;
        }
        return $this->titleFirmroles->filter(function(TitleFirmrole $titleFirmrole) use ($roleName) {
                    return $titleFirmrole->getFirmrole()->getName() === $roleName;
        });
    }

    /**
     * Return the title's title.
     *
     * @return string
     */
    public function __toString() {
        return $this->title;
    }

    /**
     * Set editionNumber
     *
     * @param integer $editionNumber
     *
     * @return Title
     */
    public function setEditionNumber($editionNumber)
    {
        $this->editionNumber = $editionNumber;

        return $this;
    }

    /**
     * Get editionNumber
     *
     * @return integer
     */
    public function getEditionNumber()
    {
        return $this->editionNumber;
    }

    /**
     * Set finalattempt
     *
     * @param boolean $finalattempt
     *
     * @return Title
     */
    public function setFinalattempt($finalattempt)
    {
        $this->finalattempt = $finalattempt;

        return $this;
    }

    /**
     * Get finalattempt
     *
     * @return boolean
     */
    public function getFinalattempt()
    {
        return $this->finalattempt;
    }

    /**
     * Set colophon.
     *
     * @param string|null $colophon
     *
     * @return Title
     */
    public function setColophon($colophon = null)
    {
        $this->colophon = $colophon;

        return $this;
    }

    /**
     * Get colophon.
     *
     * @return string|null
     */
    public function getColophon()
    {
        return $this->colophon;
    }

    public function setTitleSources($titleSources) {
        $this->titleSources = $titleSources;
    }

    /**
     * Add titleSource.
     *
     * @param \AppBundle\Entity\TitleSource $titleSource
     *
     * @return Title
     */
    public function addTitleSource(\AppBundle\Entity\TitleSource $titleSource)
    {
        $this->titleSources[] = $titleSource;

        return $this;
    }

    /**
     * Remove titleSource.
     *
     * @param \AppBundle\Entity\TitleSource $titleSource
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTitleSource(\AppBundle\Entity\TitleSource $titleSource)
    {
        return $this->titleSources->removeElement($titleSource);
    }

    /**
     * Get titleSources.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTitleSources()
    {
        return $this->titleSources;
    }
}
