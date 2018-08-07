<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrlandoBiblio
 *
 * @ORM\Table(name="orlando_biblio")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrlandoBiblioRepository")
 */
class OrlandoBiblio
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="BI_ID", type="integer", nullable=false)
     */
    private $orlandoId;

    /**
     * @var string
     *
     * @ORM\Column(name="WORKFORM", type="string", length=24, nullable=true)
     */
    private $workform;

    /**
     * @var string
     *
     * @ORM\Column(name="AUTHOR", type="text", nullable=true)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="EDITOR", type="text", nullable=true)
     */
    private $editor;

    /**
     * @var string
     *
     * @ORM\Column(name="INTRODUCTION", type="text", nullable=true)
     */
    private $introduction;

    /**
     * @var string
     *
     * @ORM\Column(name="TRANSLATOR", type="text", nullable=true)
     */
    private $translator;

    /**
     * @var string
     *
     * @ORM\Column(name="ILLUSTRATOR", type="text", nullable=true)
     */
    private $illustrator;

    /**
     * @var string
     *
     * @ORM\Column(name="COMPILER", type="text", nullable=true)
     */
    private $compiler;

    /**
     * @var string
     *
     * @ORM\Column(name="ANALYTIC_STANDARD_TITLE", type="string", length=200, nullable=true)
     */
    private $analyticStandardTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="ANALYTIC_ALTERNATE_TITLE", type="string", length=240, nullable=true)
     */
    private $analyticAlternateTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="MONOGRAPHIC_STANDARD_TITLE", type="string", length=240, nullable=true)
     */
    private $monographicStandardTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="MONOGRAPHIC_ALTERNATE_TITLE", type="string", length=240, nullable=true)
     */
    private $monographicAlternateTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="IMPRINT_DATE_OF_PUBLICATION", type="string", length=30, nullable=true)
     */
    private $imprintDateOfPublication;

    /**
     * @var string
     *
     * @ORM\Column(name="IMPRINT_DATE_OF_ORIGINAL_PUBLICATION", type="string", length=30, nullable=true)
     */
    private $imprintDateOfOriginalPublication;

    /**
     * @var string
     *
     * @ORM\Column(name="IMPRINT_PUBLISHER", type="string", length=200, nullable=true)
     */
    private $imprintPublisher;

    /**
     * @var string
     *
     * @ORM\Column(name="IMPRINT_PLACE_OF_PUBLICATION", type="string", length=100, nullable=true)
     */
    private $imprintPlaceOfPublication;

    /**
     * @var string
     *
     * @ORM\Column(name="IMPRINT_COVER_TITLE", type="text", nullable=true)
     */
    private $imprintCoverTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="IMPRINT_EDITION", type="string", length=50, nullable=true)
     */
    private $imprintEdition;

    /**
     * @var string
     *
     * @ORM\Column(name="IMPRINT_DATE_OF_ACCESS", type="string", length=20, nullable=true)
     */
    private $imprintDateOfAccess;

    /**
     * @var string
     *
     * @ORM\Column(name="SERIES_STANDARD_TITLE", type="string", length=1000, nullable=true)
     */
    private $seriesStandardTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="SERIES_ALTERNATE_TITLE", type="string", length=200, nullable=true)
     */
    private $seriesAlternateTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="SERIES_VOLUME_ID", type="string", length=50, nullable=true)
     */
    private $seriesVolumeId;

    /**
     * @var string
     *
     * @ORM\Column(name="SCOPE_MEDIUM", type="string", length=50, nullable=true)
     */
    private $scopeMedium;

    /**
     * @var string
     *
     * @ORM\Column(name="SCOPE_ISSUE_ID", type="string", length=10, nullable=true)
     */
    private $scopeIssueId;

    /**
     * @var string
     *
     * @ORM\Column(name="SCOPE_PAGES", type="string", length=50, nullable=true)
     */
    private $scopePages;

    /**
     * @var string
     *
     * @ORM\Column(name="SCOPE_VOLUME_COUNT", type="string", length=50, nullable=true)
     */
    private $scopeVolumeCount;

    /**
     * @var string
     *
     * @ORM\Column(name="LOCATION_URN", type="string", length=200, nullable=true)
     */
    private $locationUrn;

    /**
     * @var string
     *
     * @ORM\Column(name="LOCATION_CALL_NO", type="string", length=200, nullable=true)
     */
    private $locationCallNo;

    /**
     * @var string
     *
     * @ORM\Column(name="SCHOLARNOTES", type="text", nullable=true)
     */
    private $scholarnotes;

    /**
     * @var string
     *
     * @ORM\Column(name="RESEARCHNOTES", type="text", nullable=true)
     */
    private $researchnotes;


}

