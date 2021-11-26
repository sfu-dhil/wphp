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
 * OrlandoBiblio.
 *
 * @ORM\Table(name="orlando_biblio",
 *     indexes={
 *         @ORM\Index(name="orlandobiblio_biid_idx", columns={"BI_ID"}),
 *         @ORM\Index(name="orlandobiblio_ft", columns={"AUTHOR", "MONOGRAPHIC_STANDARD_TITLE"}, flags={"fulltext"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\OrlandoBiblioRepository")
 */
class OrlandoBiblio {
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\Column(name="BI_ID", type="integer", nullable=false)
     */
    private int $orlandoId;

    /**
     * @ORM\Column(name="WORKFORM", type="string", length=24, nullable=true)
     */
    private ?string  $workform = null;

    /**
     * @ORM\Column(name="AUTHOR", type="text", nullable=true)
     */
    private ?string  $author = null;

    /**
     * @ORM\Column(name="EDITOR", type="text", nullable=true)
     */
    private ?string $editor = null;

    /**
     * @ORM\Column(name="INTRODUCTION", type="text", nullable=true)
     */
    private ?string  $introduction = null;

    /**
     * @ORM\Column(name="TRANSLATOR", type="text", nullable=true)
     */
    private ?string  $translator = null;

    /**
     * @ORM\Column(name="ILLUSTRATOR", type="text", nullable=true)
     */
    private ?string  $illustrator = null;

    /**
     * @ORM\Column(name="COMPILER", type="text", nullable=true)
     */
    private ?string  $compiler = null;

    /**
     * @ORM\Column(name="ANALYTIC_STANDARD_TITLE", type="string", length=200, nullable=true)
     */
    private ?string $analyticStandardTitle = null;

    /**
     * @ORM\Column(name="ANALYTIC_ALTERNATE_TITLE", type="string", length=240, nullable=true)
     */
    private ?string $analyticAlternateTitle = null;

    /**
     * @ORM\Column(name="MONOGRAPHIC_STANDARD_TITLE", type="string", length=240, nullable=true)
     */
    private ?string $monographicStandardTitle = null;

    /**
     * @ORM\Column(name="MONOGRAPHIC_ALTERNATE_TITLE", type="string", length=240, nullable=true)
     */
    private ?string  $monographicAlternateTitle = null;

    /**
     * @ORM\Column(name="IMPRINT_DATE_OF_PUBLICATION", type="string", length=30, nullable=true)
     */
    private ?string  $imprintDateOfPublication = null;

    /**
     * @ORM\Column(name="IMPRINT_DATE_OF_ORIGINAL_PUBLICATION", type="string", length=30, nullable=true)
     */
    private ?string $imprintDateOfOriginalPublication = null;

    /**
     * @ORM\Column(name="IMPRINT_PUBLISHER", type="string", length=200, nullable=true)
     */
    private ?string $imprintPublisher = null;

    /**
     * @ORM\Column(name="IMPRINT_PLACE_OF_PUBLICATION", type="string", length=100, nullable=true)
     */
    private ?string  $imprintPlaceOfPublication = null;

    /**
     * @ORM\Column(name="IMPRINT_COVER_TITLE", type="text", nullable=true)
     */
    private ?string  $imprintCoverTitle = null;

    /**
     * @ORM\Column(name="IMPRINT_EDITION", type="string", length=50, nullable=true)
     */
    private ?string  $imprintEdition = null;

    /**
     * @ORM\Column(name="IMPRINT_DATE_OF_ACCESS", type="string", length=20, nullable=true)
     */
    private ?string  $imprintDateOfAccess = null;

    /**
     * @ORM\Column(name="SERIES_STANDARD_TITLE", type="string", length=1000, nullable=true)
     */
    private ?string $seriesStandardTitle = null;

    /**
     * @ORM\Column(name="SERIES_ALTERNATE_TITLE", type="string", length=200, nullable=true)
     */
    private ?string  $seriesAlternateTitle = null;

    /**
     * @ORM\Column(name="SERIES_VOLUME_ID", type="string", length=50, nullable=true)
     */
    private ?string  $seriesVolumeId = null;

    /**
     * @ORM\Column(name="SCOPE_MEDIUM", type="string", length=50, nullable=true)
     */
    private ?string  $scopeMedium = null;

    /**
     * @ORM\Column(name="SCOPE_ISSUE_ID", type="string", length=10, nullable=true)
     */
    private ?string  $scopeIssueId = null;

    /**
     * @ORM\Column(name="SCOPE_PAGES", type="string", length=50, nullable=true)
     */
    private ?string  $scopePages = null;

    /**
     * @ORM\Column(name="SCOPE_VOLUME_COUNT", type="string", length=50, nullable=true)
     */
    private ?string  $scopeVolumeCount = null;

    /**
     * @ORM\Column(name="LOCATION_URN", type="string", length=200, nullable=true)
     */
    private ?string $locationUrn = null;

    /**
     * @ORM\Column(name="LOCATION_CALL_NO", type="string", length=200, nullable=true)
     */
    private ?string  $locationCallNo = null;

    /**
     * @ORM\Column(name="SCHOLARNOTES", type="text", nullable=true)
     */
    private ?string  $scholarnotes = null;

    /**
     * @ORM\Column(name="RESEARCHNOTES", type="text", nullable=true)
     */
    private ?string  $researchnotes = null;

    public function __toString() : string {
        return $this->analyticStandardTitle;
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function getOrlandoId() : ?int {
        return $this->orlandoId;
    }

    public function setOrlandoId(int $orlandoId) : self {
        $this->orlandoId = $orlandoId;

        return $this;
    }

    public function getWorkform() : ?string {
        return $this->workform;
    }

    public function setWorkform(?string $workform) : self {
        $this->workform = $workform;

        return $this;
    }

    public function getAuthor() : ?string {
        return $this->author;
    }

    public function setAuthor(?string $author) : self {
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

    public function getIntroduction() : ?string {
        return $this->introduction;
    }

    public function setIntroduction(?string $introduction) : self {
        $this->introduction = $introduction;

        return $this;
    }

    public function getTranslator() : ?string {
        return $this->translator;
    }

    public function setTranslator(?string $translator) : self {
        $this->translator = $translator;

        return $this;
    }

    public function getIllustrator() : ?string {
        return $this->illustrator;
    }

    public function setIllustrator(?string $illustrator) : self {
        $this->illustrator = $illustrator;

        return $this;
    }

    public function getCompiler() : ?string {
        return $this->compiler;
    }

    public function setCompiler(?string $compiler) : self {
        $this->compiler = $compiler;

        return $this;
    }

    public function getAnalyticStandardTitle() : ?string {
        return $this->analyticStandardTitle;
    }

    public function setAnalyticStandardTitle(?string $analyticStandardTitle) : self {
        $this->analyticStandardTitle = $analyticStandardTitle;

        return $this;
    }

    public function getAnalyticAlternateTitle() : ?string {
        return $this->analyticAlternateTitle;
    }

    public function setAnalyticAlternateTitle(?string $analyticAlternateTitle) : self {
        $this->analyticAlternateTitle = $analyticAlternateTitle;

        return $this;
    }

    public function getMonographicStandardTitle() : ?string {
        return $this->monographicStandardTitle;
    }

    public function setMonographicStandardTitle(?string $monographicStandardTitle) : self {
        $this->monographicStandardTitle = $monographicStandardTitle;

        return $this;
    }

    public function getMonographicAlternateTitle() : ?string {
        return $this->monographicAlternateTitle;
    }

    public function setMonographicAlternateTitle(?string $monographicAlternateTitle) : self {
        $this->monographicAlternateTitle = $monographicAlternateTitle;

        return $this;
    }

    public function getImprintDateOfPublication() : ?string {
        return $this->imprintDateOfPublication;
    }

    public function setImprintDateOfPublication(?string $imprintDateOfPublication) : self {
        $this->imprintDateOfPublication = $imprintDateOfPublication;

        return $this;
    }

    public function getImprintDateOfOriginalPublication() : ?string {
        return $this->imprintDateOfOriginalPublication;
    }

    public function setImprintDateOfOriginalPublication(?string $imprintDateOfOriginalPublication) : self {
        $this->imprintDateOfOriginalPublication = $imprintDateOfOriginalPublication;

        return $this;
    }

    public function getImprintPublisher() : ?string {
        return $this->imprintPublisher;
    }

    public function setImprintPublisher(?string $imprintPublisher) : self {
        $this->imprintPublisher = $imprintPublisher;

        return $this;
    }

    public function getImprintPlaceOfPublication() : ?string {
        return $this->imprintPlaceOfPublication;
    }

    public function setImprintPlaceOfPublication(?string $imprintPlaceOfPublication) : self {
        $this->imprintPlaceOfPublication = $imprintPlaceOfPublication;

        return $this;
    }

    public function getImprintCoverTitle() : ?string {
        return $this->imprintCoverTitle;
    }

    public function setImprintCoverTitle(?string $imprintCoverTitle) : self {
        $this->imprintCoverTitle = $imprintCoverTitle;

        return $this;
    }

    public function getImprintEdition() : ?string {
        return $this->imprintEdition;
    }

    public function setImprintEdition(?string $imprintEdition) : self {
        $this->imprintEdition = $imprintEdition;

        return $this;
    }

    public function getImprintDateOfAccess() : ?string {
        return $this->imprintDateOfAccess;
    }

    public function setImprintDateOfAccess(?string $imprintDateOfAccess) : self {
        $this->imprintDateOfAccess = $imprintDateOfAccess;

        return $this;
    }

    public function getSeriesStandardTitle() : ?string {
        return $this->seriesStandardTitle;
    }

    public function setSeriesStandardTitle(?string $seriesStandardTitle) : self {
        $this->seriesStandardTitle = $seriesStandardTitle;

        return $this;
    }

    public function getSeriesAlternateTitle() : ?string {
        return $this->seriesAlternateTitle;
    }

    public function setSeriesAlternateTitle(?string $seriesAlternateTitle) : self {
        $this->seriesAlternateTitle = $seriesAlternateTitle;

        return $this;
    }

    public function getSeriesVolumeId() : ?string {
        return $this->seriesVolumeId;
    }

    public function setSeriesVolumeId(?string $seriesVolumeId) : self {
        $this->seriesVolumeId = $seriesVolumeId;

        return $this;
    }

    public function getScopeMedium() : ?string {
        return $this->scopeMedium;
    }

    public function setScopeMedium(?string $scopeMedium) : self {
        $this->scopeMedium = $scopeMedium;

        return $this;
    }

    public function getScopeIssueId() : ?string {
        return $this->scopeIssueId;
    }

    public function setScopeIssueId(?string $scopeIssueId) : self {
        $this->scopeIssueId = $scopeIssueId;

        return $this;
    }

    public function getScopePages() : ?string {
        return $this->scopePages;
    }

    public function setScopePages(?string $scopePages) : self {
        $this->scopePages = $scopePages;

        return $this;
    }

    public function getScopeVolumeCount() : ?string {
        return $this->scopeVolumeCount;
    }

    public function setScopeVolumeCount(?string $scopeVolumeCount) : self {
        $this->scopeVolumeCount = $scopeVolumeCount;

        return $this;
    }

    public function getLocationUrn() : ?string {
        return $this->locationUrn;
    }

    public function setLocationUrn(?string $locationUrn) : self {
        $this->locationUrn = $locationUrn;

        return $this;
    }

    public function getLocationCallNo() : ?string {
        return $this->locationCallNo;
    }

    public function setLocationCallNo(?string $locationCallNo) : self {
        $this->locationCallNo = $locationCallNo;

        return $this;
    }

    public function getScholarnotes() : ?string {
        return $this->scholarnotes;
    }

    public function setScholarnotes(?string $scholarnotes) : self {
        $this->scholarnotes = $scholarnotes;

        return $this;
    }

    public function getResearchnotes() : ?string {
        return $this->researchnotes;
    }

    public function setResearchnotes(?string $researchnotes) : self {
        $this->researchnotes = $researchnotes;

        return $this;
    }
}
