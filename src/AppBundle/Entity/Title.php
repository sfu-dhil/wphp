<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Title
 *
 * @ORM\Table(name="title", 
 *  indexes={
 *      @ORM\Index(name="location_of_printing", columns={"location_of_printing"}), 
 *      @ORM\Index(name="format_id", columns={"format_id"}), 
 *      @ORM\Index(name="genre_id", columns={"genre_id"}), 
 *      @ORM\Index(name="source", columns={"source"}), 
 *      @ORM\Index(name="source2", columns={"source2"}), 
 *      @ORM\Index(name="title", columns={"title"}, flags={"fulltext"}), 
 *      @ORM\Index(name="author", columns={"signed_author"}, flags={"fulltext"}), 
 *      @ORM\Index(name="titleauthor", columns={"title", "signed_author"}, flags={"fulltext"}), 
 *      @ORM\Index(name="imprint", columns={"imprint"}, flags={"fulltext"})
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TitleRepository")
 */
class Title
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=1000, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="signed_author", type="text", length=16777215, nullable=true)
     */
    private $signedAuthor;

    /**
     * @var string
     *
     * @ORM\Column(name="surrogate", type="text", length=16777215, nullable=true)
     */
    private $surrogate;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudonym", type="string", length=255, nullable=true)
     */
    private $pseudonym;

    /**
     * @var string
     *
     * @ORM\Column(name="imprint", type="text", length=16777215, nullable=true)
     */
    private $imprint;

    /**
     * @var boolean
     *
     * @ORM\Column(name="selfpublished", type="boolean", nullable=false)
     */
    private $selfpublished = '0';

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
     * @ORM\Column(name="size_l", type="boolean", nullable=true)
     */
    private $sizeL;

    /**
     * @var boolean
     *
     * @ORM\Column(name="size_w", type="boolean", nullable=true)
     */
    private $sizeW;

    /**
     * @var string
     *
     * @ORM\Column(name="edition", type="string", length=200, nullable=true)
     */
    private $edition;

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
     * @ORM\Column(name="source_id", type="string", length=20, nullable=true)
     */
    private $sourceId;

    /**
     * @var string
     *
     * @ORM\Column(name="source2_id", type="string", length=20, nullable=true)
     */
    private $source2Id;

    /**
     * @var string
     *
     * @ORM\Column(name="shelfmark", type="text", length=16777215, nullable=true)
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
     * @var string
     *
     * @ORM\Column(name="notes", type="text", length=16777215, nullable=true)
     */
    private $notes;

    /**
     * @var \Geonames
     *
     * @ORM\ManyToOne(targetEntity="Geonames")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="location_of_printing", referencedColumnName="geonameid")
     * })
     */
    private $locationOfPrinting;

    /**
     * @var \Format
     *
     * @ORM\ManyToOne(targetEntity="Format")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="format_id", referencedColumnName="id")
     * })
     */
    private $format;

    /**
     * @var \Genre
     *
     * @ORM\ManyToOne(targetEntity="Genre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="genre_id", referencedColumnName="id")
     * })
     */
    private $genre;

    /**
     * @var \Source
     *
     * @ORM\ManyToOne(targetEntity="Source")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="source", referencedColumnName="id")
     * })
     */
    private $source;

    /**
     * @var \Source
     *
     * @ORM\ManyToOne(targetEntity="Source")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="source2", referencedColumnName="id")
     * })
     */
    private $source2;

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
     * Constructor
     */
    public function __construct()
    {
        $this->titleRoles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->titleFirmroles = new \Doctrine\Common\Collections\ArrayCollection();        
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Title
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set signedAuthor
     *
     * @param string $signedAuthor
     *
     * @return Title
     */
    public function setSignedAuthor($signedAuthor)
    {
        $this->signedAuthor = $signedAuthor;

        return $this;
    }

    /**
     * Get signedAuthor
     *
     * @return string
     */
    public function getSignedAuthor()
    {
        return $this->signedAuthor;
    }

    /**
     * Set surrogate
     *
     * @param string $surrogate
     *
     * @return Title
     */
    public function setSurrogate($surrogate)
    {
        $this->surrogate = $surrogate;

        return $this;
    }

    /**
     * Get surrogate
     *
     * @return string
     */
    public function getSurrogate()
    {
        return $this->surrogate;
    }

    /**
     * Set pseudonym
     *
     * @param string $pseudonym
     *
     * @return Title
     */
    public function setPseudonym($pseudonym)
    {
        $this->pseudonym = $pseudonym;

        return $this;
    }

    /**
     * Get pseudonym
     *
     * @return string
     */
    public function getPseudonym()
    {
        return $this->pseudonym;
    }

    /**
     * Set imprint
     *
     * @param string $imprint
     *
     * @return Title
     */
    public function setImprint($imprint)
    {
        $this->imprint = $imprint;

        return $this;
    }

    /**
     * Get imprint
     *
     * @return string
     */
    public function getImprint()
    {
        return $this->imprint;
    }

    /**
     * Set selfpublished
     *
     * @param boolean $selfpublished
     *
     * @return Title
     */
    public function setSelfpublished($selfpublished)
    {
        $this->selfpublished = $selfpublished;

        return $this;
    }

    /**
     * Get selfpublished
     *
     * @return boolean
     */
    public function getSelfpublished()
    {
        return $this->selfpublished;
    }

    /**
     * Set pubdate
     *
     * @param string $pubdate
     *
     * @return Title
     */
    public function setPubdate($pubdate)
    {
        $this->pubdate = $pubdate;

        return $this;
    }

    /**
     * Get pubdate
     *
     * @return string
     */
    public function getPubdate()
    {
        return $this->pubdate;
    }

    /**
     * Set dateOfFirstPublication
     *
     * @param string $dateOfFirstPublication
     *
     * @return Title
     */
    public function setDateOfFirstPublication($dateOfFirstPublication)
    {
        $this->dateOfFirstPublication = $dateOfFirstPublication;

        return $this;
    }

    /**
     * Get dateOfFirstPublication
     *
     * @return string
     */
    public function getDateOfFirstPublication()
    {
        return $this->dateOfFirstPublication;
    }

    /**
     * Set sizeL
     *
     * @param boolean $sizeL
     *
     * @return Title
     */
    public function setSizeL($sizeL)
    {
        $this->sizeL = $sizeL;

        return $this;
    }

    /**
     * Get sizeL
     *
     * @return boolean
     */
    public function getSizeL()
    {
        return $this->sizeL;
    }

    /**
     * Set sizeW
     *
     * @param boolean $sizeW
     *
     * @return Title
     */
    public function setSizeW($sizeW)
    {
        $this->sizeW = $sizeW;

        return $this;
    }

    /**
     * Get sizeW
     *
     * @return boolean
     */
    public function getSizeW()
    {
        return $this->sizeW;
    }

    /**
     * Set edition
     *
     * @param string $edition
     *
     * @return Title
     */
    public function setEdition($edition)
    {
        $this->edition = $edition;

        return $this;
    }

    /**
     * Get edition
     *
     * @return string
     */
    public function getEdition()
    {
        return $this->edition;
    }

    /**
     * Set volumes
     *
     * @param boolean $volumes
     *
     * @return Title
     */
    public function setVolumes($volumes)
    {
        $this->volumes = $volumes;

        return $this;
    }

    /**
     * Get volumes
     *
     * @return boolean
     */
    public function getVolumes()
    {
        return $this->volumes;
    }

    /**
     * Set pagination
     *
     * @param string $pagination
     *
     * @return Title
     */
    public function setPagination($pagination)
    {
        $this->pagination = $pagination;

        return $this;
    }

    /**
     * Get pagination
     *
     * @return string
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * Set pricePound
     *
     * @param integer $pricePound
     *
     * @return Title
     */
    public function setPricePound($pricePound)
    {
        $this->pricePound = $pricePound;

        return $this;
    }

    /**
     * Get pricePound
     *
     * @return integer
     */
    public function getPricePound()
    {
        return $this->pricePound;
    }

    /**
     * Set priceShilling
     *
     * @param integer $priceShilling
     *
     * @return Title
     */
    public function setPriceShilling($priceShilling)
    {
        $this->priceShilling = $priceShilling;

        return $this;
    }

    /**
     * Get priceShilling
     *
     * @return integer
     */
    public function getPriceShilling()
    {
        return $this->priceShilling;
    }

    /**
     * Set pricePence
     *
     * @param string $pricePence
     *
     * @return Title
     */
    public function setPricePence($pricePence)
    {
        $this->pricePence = $pricePence;

        return $this;
    }

    /**
     * Get pricePence
     *
     * @return string
     */
    public function getPricePence()
    {
        return $this->pricePence;
    }
		
		/**
     * Get the totalPrice in pence.
     *
     * @return integer
     */
		public function getTotalPrice()
		{
			$totalPrice = 0;
			
			if ($this->pricePound && is_int($this->pricePound)) {
				$totalPrice += $this->pricePound * 240;
			}
			
			if ($this->priceShilling && is_int($this->priceShilling)) {
				$totalPrice += $this->priceShilling * 12;
			}
			if ($this->pricePence && is_int($this->pricePence)) {
				$totalPrice += $this->pricePence;
			}
			
			return $totalPrice;
		}
		
    /**
     * Set sourceId
     *
     * @param string $sourceId
     *
     * @return Title
     */
    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;

        return $this;
    }

    /**
     * Get sourceId
     *
     * @return string
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * Set source2Id
     *
     * @param string $source2Id
     *
     * @return Title
     */
    public function setSource2Id($source2Id)
    {
        $this->source2Id = $source2Id;

        return $this;
    }

    /**
     * Get source2Id
     *
     * @return string
     */
    public function getSource2Id()
    {
        return $this->source2Id;
    }

    /**
     * Set shelfmark
     *
     * @param string $shelfmark
     *
     * @return Title
     */
    public function setShelfmark($shelfmark)
    {
        $this->shelfmark = $shelfmark;

        return $this;
    }

    /**
     * Get shelfmark
     *
     * @return string
     */
    public function getShelfmark()
    {
        return $this->shelfmark;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     *
     * @return Title
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * Get checked
     *
     * @return boolean
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Set finalcheck
     *
     * @param boolean $finalcheck
     *
     * @return Title
     */
    public function setFinalcheck($finalcheck)
    {
        $this->finalcheck = $finalcheck;

        return $this;
    }

    /**
     * Get finalcheck
     *
     * @return boolean
     */
    public function getFinalcheck()
    {
        return $this->finalcheck;
    }

    /**
     * Set notes
     *
     * @param string $notes
     *
     * @return Title
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set locationOfPrinting
     *
     * @param \AppBundle\Entity\Geonames $locationOfPrinting
     *
     * @return Title
     */
    public function setLocationOfPrinting(\AppBundle\Entity\Geonames $locationOfPrinting = null)
    {
        $this->locationOfPrinting = $locationOfPrinting;

        return $this;
    }

    /**
     * Get locationOfPrinting
     *
     * @return \AppBundle\Entity\Geonames
     */
    public function getLocationOfPrinting()
    {
        return $this->locationOfPrinting;
    }

    /**
     * Set format
     *
     * @param \AppBundle\Entity\Format $format
     *
     * @return Title
     */
    public function setFormat(\AppBundle\Entity\Format $format = null)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format
     *
     * @return \AppBundle\Entity\Format
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set genre
     *
     * @param \AppBundle\Entity\Genre $genre
     *
     * @return Title
     */
    public function setGenre(\AppBundle\Entity\Genre $genre = null)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return \AppBundle\Entity\Genre
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set source
     *
     * @param \AppBundle\Entity\Source $source
     *
     * @return Title
     */
    public function setSource(\AppBundle\Entity\Source $source = null)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return \AppBundle\Entity\Source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set source2
     *
     * @param \AppBundle\Entity\Source $source2
     *
     * @return Title
     */
    public function setSource2(\AppBundle\Entity\Source $source2 = null)
    {
        $this->source2 = $source2;

        return $this;
    }

    /**
     * Get source2
     *
     * @return \AppBundle\Entity\Source
     */
    public function getSource2()
    {
        return $this->source2;
    }

    /**
     * Add titleRole
     *
     * @param \AppBundle\Entity\TitleRole $titleRole
     *
     * @return Title
     */
    public function addTitleRole(\AppBundle\Entity\TitleRole $titleRole)
    {
        $this->titleRoles[] = $titleRole;

        return $this;
    }

    /**
     * Remove titleRole
     *
     * @param \AppBundle\Entity\TitleRole $titleRole
     */
    public function removeTitleRole(\AppBundle\Entity\TitleRole $titleRole)
    {
        $this->titleRoles->removeElement($titleRole);
    }

    /**
     * Get titleRoles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTitleRoles($name = null)
    {
      if($name === null) {
        return $this->titleRoles;
      }
      return $this->titleRoles->filter(function(TitleRole $titleRole) use ($name) {
        return $titleRole->getRole()->getName() === $name;
      });
    }


    /**
     * Add titleFirmrole
     *
     * @param \AppBundle\Entity\TitleFirmrole $titleFirmrole
     *
     * @return Title
     */
    public function addTitleFirmrole(\AppBundle\Entity\TitleFirmrole $titleFirmrole)
    {
        $this->titleFirmroles[] = $titleFirmrole;

        return $this;
    }

    /**
     * Remove titleFirmrole
     *
     * @param \AppBundle\Entity\TitleFirmrole $titleFirmrole
     */
    public function removeTitleFirmrole(\AppBundle\Entity\TitleFirmrole $titleFirmrole)
    {
        $this->titleFirmroles->removeElement($titleFirmrole);
    }

    /**
     * Get titleFirmroles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTitleFirmroles()
    {
        return $this->titleFirmroles;
    }
	
	public function __toString() {
		return $this->title;
	}
}
