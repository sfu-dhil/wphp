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
 * OrlandoBiblio.
 *
 * @ORM\Table(name="orlando_biblio",
 *  indexes={
 *      @ORM\Index(name="orlandobiblio_biid_idx", columns={"BI_ID"}),
 *      @ORM\Index(name="orlandobiblio_ft", columns={"AUTHOR", "MONOGRAPHIC_STANDARD_TITLE"}, flags={"fulltext"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\OrlandoBiblioRepository")
 */
class OrlandoBiblio {
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
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

    public function __toString() : string {
        return $this->analyticStandardTitle;
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
     * Set orlandoId.
     *
     * @param int $orlandoId
     *
     * @return OrlandoBiblio
     */
    public function setOrlandoId($orlandoId) {
        $this->orlandoId = $orlandoId;

        return $this;
    }

    /**
     * Get orlandoId.
     *
     * @return int
     */
    public function getOrlandoId() {
        return $this->orlandoId;
    }

    /**
     * Set workform.
     *
     * @param string $workform
     *
     * @return OrlandoBiblio
     */
    public function setWorkform($workform) {
        $this->workform = $workform;

        return $this;
    }

    /**
     * Get workform.
     *
     * @return string
     */
    public function getWorkform() {
        return $this->workform;
    }

    /**
     * Set author.
     *
     * @param string $author
     *
     * @return OrlandoBiblio
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
     * @return OrlandoBiblio
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
     * Set introduction.
     *
     * @param string $introduction
     *
     * @return OrlandoBiblio
     */
    public function setIntroduction($introduction) {
        $this->introduction = $introduction;

        return $this;
    }

    /**
     * Get introduction.
     *
     * @return string
     */
    public function getIntroduction() {
        return $this->introduction;
    }

    /**
     * Set translator.
     *
     * @param string $translator
     *
     * @return OrlandoBiblio
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
     * Set illustrator.
     *
     * @param string $illustrator
     *
     * @return OrlandoBiblio
     */
    public function setIllustrator($illustrator) {
        $this->illustrator = $illustrator;

        return $this;
    }

    /**
     * Get illustrator.
     *
     * @return string
     */
    public function getIllustrator() {
        return $this->illustrator;
    }

    /**
     * Set compiler.
     *
     * @param string $compiler
     *
     * @return OrlandoBiblio
     */
    public function setCompiler($compiler) {
        $this->compiler = $compiler;

        return $this;
    }

    /**
     * Get compiler.
     *
     * @return string
     */
    public function getCompiler() {
        return $this->compiler;
    }

    /**
     * Set analyticStandardTitle.
     *
     * @param string $analyticStandardTitle
     *
     * @return OrlandoBiblio
     */
    public function setAnalyticStandardTitle($analyticStandardTitle) {
        $this->analyticStandardTitle = $analyticStandardTitle;

        return $this;
    }

    /**
     * Get analyticStandardTitle.
     *
     * @return string
     */
    public function getAnalyticStandardTitle() {
        return $this->analyticStandardTitle;
    }

    /**
     * Set analyticAlternateTitle.
     *
     * @param string $analyticAlternateTitle
     *
     * @return OrlandoBiblio
     */
    public function setAnalyticAlternateTitle($analyticAlternateTitle) {
        $this->analyticAlternateTitle = $analyticAlternateTitle;

        return $this;
    }

    /**
     * Get analyticAlternateTitle.
     *
     * @return string
     */
    public function getAnalyticAlternateTitle() {
        return $this->analyticAlternateTitle;
    }

    /**
     * Set monographicStandardTitle.
     *
     * @param string $monographicStandardTitle
     *
     * @return OrlandoBiblio
     */
    public function setMonographicStandardTitle($monographicStandardTitle) {
        $this->monographicStandardTitle = $monographicStandardTitle;

        return $this;
    }

    /**
     * Get monographicStandardTitle.
     *
     * @return string
     */
    public function getMonographicStandardTitle() {
        return $this->monographicStandardTitle;
    }

    /**
     * Set monographicAlternateTitle.
     *
     * @param string $monographicAlternateTitle
     *
     * @return OrlandoBiblio
     */
    public function setMonographicAlternateTitle($monographicAlternateTitle) {
        $this->monographicAlternateTitle = $monographicAlternateTitle;

        return $this;
    }

    /**
     * Get monographicAlternateTitle.
     *
     * @return string
     */
    public function getMonographicAlternateTitle() {
        return $this->monographicAlternateTitle;
    }

    /**
     * Set imprintDateOfPublication.
     *
     * @param string $imprintDateOfPublication
     *
     * @return OrlandoBiblio
     */
    public function setImprintDateOfPublication($imprintDateOfPublication) {
        $this->imprintDateOfPublication = $imprintDateOfPublication;

        return $this;
    }

    /**
     * Get imprintDateOfPublication.
     *
     * @return string
     */
    public function getImprintDateOfPublication() {
        return $this->imprintDateOfPublication;
    }

    /**
     * Set imprintDateOfOriginalPublication.
     *
     * @param string $imprintDateOfOriginalPublication
     *
     * @return OrlandoBiblio
     */
    public function setImprintDateOfOriginalPublication($imprintDateOfOriginalPublication) {
        $this->imprintDateOfOriginalPublication = $imprintDateOfOriginalPublication;

        return $this;
    }

    /**
     * Get imprintDateOfOriginalPublication.
     *
     * @return string
     */
    public function getImprintDateOfOriginalPublication() {
        return $this->imprintDateOfOriginalPublication;
    }

    /**
     * Set imprintPublisher.
     *
     * @param string $imprintPublisher
     *
     * @return OrlandoBiblio
     */
    public function setImprintPublisher($imprintPublisher) {
        $this->imprintPublisher = $imprintPublisher;

        return $this;
    }

    /**
     * Get imprintPublisher.
     *
     * @return string
     */
    public function getImprintPublisher() {
        return $this->imprintPublisher;
    }

    /**
     * Set imprintPlaceOfPublication.
     *
     * @param string $imprintPlaceOfPublication
     *
     * @return OrlandoBiblio
     */
    public function setImprintPlaceOfPublication($imprintPlaceOfPublication) {
        $this->imprintPlaceOfPublication = $imprintPlaceOfPublication;

        return $this;
    }

    /**
     * Get imprintPlaceOfPublication.
     *
     * @return string
     */
    public function getImprintPlaceOfPublication() {
        return $this->imprintPlaceOfPublication;
    }

    /**
     * Set imprintCoverTitle.
     *
     * @param string $imprintCoverTitle
     *
     * @return OrlandoBiblio
     */
    public function setImprintCoverTitle($imprintCoverTitle) {
        $this->imprintCoverTitle = $imprintCoverTitle;

        return $this;
    }

    /**
     * Get imprintCoverTitle.
     *
     * @return string
     */
    public function getImprintCoverTitle() {
        return $this->imprintCoverTitle;
    }

    /**
     * Set imprintEdition.
     *
     * @param string $imprintEdition
     *
     * @return OrlandoBiblio
     */
    public function setImprintEdition($imprintEdition) {
        $this->imprintEdition = $imprintEdition;

        return $this;
    }

    /**
     * Get imprintEdition.
     *
     * @return string
     */
    public function getImprintEdition() {
        return $this->imprintEdition;
    }

    /**
     * Set imprintDateOfAccess.
     *
     * @param string $imprintDateOfAccess
     *
     * @return OrlandoBiblio
     */
    public function setImprintDateOfAccess($imprintDateOfAccess) {
        $this->imprintDateOfAccess = $imprintDateOfAccess;

        return $this;
    }

    /**
     * Get imprintDateOfAccess.
     *
     * @return string
     */
    public function getImprintDateOfAccess() {
        return $this->imprintDateOfAccess;
    }

    /**
     * Set seriesStandardTitle.
     *
     * @param string $seriesStandardTitle
     *
     * @return OrlandoBiblio
     */
    public function setSeriesStandardTitle($seriesStandardTitle) {
        $this->seriesStandardTitle = $seriesStandardTitle;

        return $this;
    }

    /**
     * Get seriesStandardTitle.
     *
     * @return string
     */
    public function getSeriesStandardTitle() {
        return $this->seriesStandardTitle;
    }

    /**
     * Set seriesAlternateTitle.
     *
     * @param string $seriesAlternateTitle
     *
     * @return OrlandoBiblio
     */
    public function setSeriesAlternateTitle($seriesAlternateTitle) {
        $this->seriesAlternateTitle = $seriesAlternateTitle;

        return $this;
    }

    /**
     * Get seriesAlternateTitle.
     *
     * @return string
     */
    public function getSeriesAlternateTitle() {
        return $this->seriesAlternateTitle;
    }

    /**
     * Set seriesVolumeId.
     *
     * @param string $seriesVolumeId
     *
     * @return OrlandoBiblio
     */
    public function setSeriesVolumeId($seriesVolumeId) {
        $this->seriesVolumeId = $seriesVolumeId;

        return $this;
    }

    /**
     * Get seriesVolumeId.
     *
     * @return string
     */
    public function getSeriesVolumeId() {
        return $this->seriesVolumeId;
    }

    /**
     * Set scopeMedium.
     *
     * @param string $scopeMedium
     *
     * @return OrlandoBiblio
     */
    public function setScopeMedium($scopeMedium) {
        $this->scopeMedium = $scopeMedium;

        return $this;
    }

    /**
     * Get scopeMedium.
     *
     * @return string
     */
    public function getScopeMedium() {
        return $this->scopeMedium;
    }

    /**
     * Set scopeIssueId.
     *
     * @param string $scopeIssueId
     *
     * @return OrlandoBiblio
     */
    public function setScopeIssueId($scopeIssueId) {
        $this->scopeIssueId = $scopeIssueId;

        return $this;
    }

    /**
     * Get scopeIssueId.
     *
     * @return string
     */
    public function getScopeIssueId() {
        return $this->scopeIssueId;
    }

    /**
     * Set scopePages.
     *
     * @param string $scopePages
     *
     * @return OrlandoBiblio
     */
    public function setScopePages($scopePages) {
        $this->scopePages = $scopePages;

        return $this;
    }

    /**
     * Get scopePages.
     *
     * @return string
     */
    public function getScopePages() {
        return $this->scopePages;
    }

    /**
     * Set scopeVolumeCount.
     *
     * @param string $scopeVolumeCount
     *
     * @return OrlandoBiblio
     */
    public function setScopeVolumeCount($scopeVolumeCount) {
        $this->scopeVolumeCount = $scopeVolumeCount;

        return $this;
    }

    /**
     * Get scopeVolumeCount.
     *
     * @return string
     */
    public function getScopeVolumeCount() {
        return $this->scopeVolumeCount;
    }

    /**
     * Set locationUrn.
     *
     * @param string $locationUrn
     *
     * @return OrlandoBiblio
     */
    public function setLocationUrn($locationUrn) {
        $this->locationUrn = $locationUrn;

        return $this;
    }

    /**
     * Get locationUrn.
     *
     * @return string
     */
    public function getLocationUrn() {
        return $this->locationUrn;
    }

    /**
     * Set locationCallNo.
     *
     * @param string $locationCallNo
     *
     * @return OrlandoBiblio
     */
    public function setLocationCallNo($locationCallNo) {
        $this->locationCallNo = $locationCallNo;

        return $this;
    }

    /**
     * Get locationCallNo.
     *
     * @return string
     */
    public function getLocationCallNo() {
        return $this->locationCallNo;
    }

    /**
     * Set scholarnotes.
     *
     * @param string $scholarnotes
     *
     * @return OrlandoBiblio
     */
    public function setScholarnotes($scholarnotes) {
        $this->scholarnotes = $scholarnotes;

        return $this;
    }

    /**
     * Get scholarnotes.
     *
     * @return string
     */
    public function getScholarnotes() {
        return $this->scholarnotes;
    }

    /**
     * Set researchnotes.
     *
     * @param string $researchnotes
     *
     * @return OrlandoBiblio
     */
    public function setResearchnotes($researchnotes) {
        $this->researchnotes = $researchnotes;

        return $this;
    }

    /**
     * Get researchnotes.
     *
     * @return string
     */
    public function getResearchnotes() {
        return $this->researchnotes;
    }
}
