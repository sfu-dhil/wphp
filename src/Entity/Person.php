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
use Exception;
use Nines\UtilBundle\Entity\AbstractEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Person.
 *
 * @ORM\Table(name="person",
 *     indexes={
 *         @ORM\Index(name="person_full_idx", columns={"last_name", "first_name", "title"}, flags={"fulltext"}),
 *         @ORM\Index(name="person_viaf_idx", columns={"viaf_permalink"}, flags={"fulltext"}),
 *         @ORM\Index(name="person_wikipedia_idx", columns={"wikipedia_link"}, flags={"fulltext"}),
 *         @ORM\Index(name="person_image_idx", columns={"image_link"}, flags={"fulltext"}),
 *     })
 *     @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person extends AbstractEntity {
    public const MALE = 'M';

    public const FEMALE = 'F';

    public const UNKNOWN = 'U';

    public const TRANS = 'T';

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=100, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=100, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=200, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1, nullable=false, options={"default": "U"})
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="dob", type="string", length=20, nullable=true)
     */
    private $dob;

    /**
     * @var string
     *
     * @ORM\Column(name="dod", type="string", length=20, nullable=true)
     */
    private $dod;

    /**
     * @var string
     *
     * @Assert\Url
     * @ORM\Column(name="viaf_permalink", type="string", length=127, nullable=true)
     */
    private $viafUrl;

    /**
     * @var string
     *
     * @Assert\Url
     * @ORM\Column(name="wikipedia_link", type="string", length=127, nullable=true)
     */
    private $wikipediaUrl;

    /**
     * @var string
     *
     * @Assert\Url
     * @ORM\Column(name="image_link", type="string", length=255, nullable=true)
     */
    private $imageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;

    /**
     * @var bool
     *
     * @ORM\Column(name="finalcheck", type="boolean", nullable=false)
     */
    private $finalcheck = false;

    /**
     * @var Geonames
     *
     * @ORM\ManyToOne(targetEntity="Geonames", inversedBy="peopleBorn")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="city_id_of_birth", referencedColumnName="geonameid")
     * })
     */
    private $cityOfBirth;

    /**
     * @var Geonames
     *
     * @ORM\ManyToOne(targetEntity="Geonames", inversedBy="peopleDied")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="city_id_of_death", referencedColumnName="geonameid")
     * })
     */
    private $cityOfDeath;

    /**
     * @var Collection<int,TitleRole>
     * @ORM\OneToMany(targetEntity="TitleRole", mappedBy="person")
     */
    private $titleRoles;

    /**
     * @var Collection<int,Firm>
     * @ORM\ManyToMany(targetEntity="Firm", inversedBy="relatedPeople")
     */
    private $relatedFirms;

    /**
     * Construct the person entity.
     */
    public function __construct() {
        parent::__construct();
        $this->gender = self::UNKNOWN;
        $this->titleRoles = new ArrayCollection();
        $this->relatedFirms = new ArrayCollection();
    }

    /**
     * Get a string representation of the person, which is lastname, firstname.
     */
    public function __toString() : string {
        return implode(', ', array_filter([$this->lastName, $this->firstName]));
    }

    /**
     * @return string
     */
    public function getFormId() {
        return "({$this->id}) " . implode(', ', array_filter([$this->lastName, $this->firstName]));
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return self
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return self
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return self
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
     * Set gender.
     *
     * @param string $gender
     *
     * @return self
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
     * Set dob.
     *
     * @param string $dob
     *
     * @return self
     */
    public function setDob($dob) {
        $this->dob = $dob;

        return $this;
    }

    /**
     * Get dob.
     *
     * @return null|string
     */
    public function getDob() {
        if ('0000-00-00' === $this->dob) {
            return null;
        }

        return $this->dob;
    }

    /**
     * Set dod.
     *
     * @param string $dod
     *
     * @return self
     */
    public function setDod($dod) {
        $this->dod = $dod;

        return $this;
    }

    /**
     * Get dod.
     *
     * @return null|string
     */
    public function getDod() {
        if ('0000-00-00' === $this->dod) {
            return null;
        }

        return $this->dod;
    }

    /**
     * Set notes.
     *
     * @param string $notes
     *
     * @return self
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

    /**
     * Set finalcheck.
     *
     * @param bool $finalcheck
     *
     * @return self
     */
    public function setFinalcheck($finalcheck) {
        $this->finalcheck = $finalcheck;

        return $this;
    }

    /**
     * Get finalcheck.
     *
     * @return bool
     */
    public function getFinalcheck() {
        return $this->finalcheck;
    }

    /**
     * Set cityOfBirth.
     *
     * @param Geonames $cityOfBirth
     *
     * @return self
     */
    public function setCityOfBirth(?Geonames $cityOfBirth = null) {
        $this->cityOfBirth = $cityOfBirth;

        return $this;
    }

    /**
     * Get cityOfBirth.
     *
     * @return null|Geonames
     */
    public function getCityOfBirth() {
        return $this->cityOfBirth;
    }

    /**
     * Set cityOfDeath.
     *
     * @param ?Geonames $cityOfDeath
     *
     * @return self
     */
    public function setCityOfDeath(?Geonames $cityOfDeath = null) {
        $this->cityOfDeath = $cityOfDeath;

        return $this;
    }

    /**
     * Get cityOfDeath.
     *
     * @return null|Geonames
     */
    public function getCityOfDeath() {
        return $this->cityOfDeath;
    }

    /**
     * Add titleRole.
     *
     * @return self
     */
    public function addTitleRole(TitleRole $titleRole) {
        $this->titleRoles[] = $titleRole;

        return $this;
    }

    /**
     * Remove titleRole.
     */
    public function removeTitleRole(TitleRole $titleRole) : void {
        $this->titleRoles->removeElement($titleRole);
    }

    /**
     * Get titleRoles.
     *
     * @param bool $sort
     *
     * @throws Exception
     *
     * @return Collection<int,TitleRole>
     */
    public function getTitleRoles($sort = false) {
        if ( ! $sort) {
            return $this->titleRoles;
        }

        $iterator = $this->titleRoles->getIterator();
        $iterator->uasort(function (TitleRole $a, TitleRole $b) {
            $dateCmp = $a->getTitle()->getPubdate() <=> $b->getTitle()->getPubdate();
            if (0 !== $dateCmp) {
                return $dateCmp;
            }

            return strcasecmp($a->getTitle()->getTitle(), $b->getTitle()->getTitle());
        });

        return new ArrayCollection(iterator_to_array($iterator));
    }

    /**
     * Set viafUrl.
     *
     * @param null|string $viafUrl
     *
     * @return self
     */
    public function setViafUrl($viafUrl = null) {
        $this->viafUrl = $viafUrl;

        return $this;
    }

    /**
     * Get viafUrl.
     *
     * @return null|string
     */
    public function getViafUrl() {
        return $this->viafUrl;
    }

    /**
     * Set wikipediaUrl.
     *
     * @param null|string $wikipediaUrl
     *
     * @return self
     */
    public function setWikipediaUrl($wikipediaUrl = null) {
        $this->wikipediaUrl = $wikipediaUrl;

        return $this;
    }

    /**
     * Get wikipediaUrl.
     *
     * @return null|string
     */
    public function getWikipediaUrl() {
        return $this->wikipediaUrl;
    }

    /**
     * Set imageUrl.
     *
     * @param null|string $imageUrl
     *
     * @return self
     */
    public function setImageUrl($imageUrl = null) {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get imageUrl.
     *
     * @return null|string
     */
    public function getImageUrl() {
        return $this->imageUrl;
    }

    /**
     * @return Collection<int,Firm>
     */
    public function getRelatedFirms() : Collection {
        return $this->relatedFirms;
    }

    public function addRelatedFirm(Firm $relatedFirm) : self {
        if ( ! $this->relatedFirms->contains($relatedFirm)) {
            $this->relatedFirms[] = $relatedFirm;
        }

        return $this;
    }

    public function removeRelatedFirm(Firm $relatedFirm) : self {
        $this->relatedFirms->removeElement($relatedFirm);

        return $this;
    }
}
