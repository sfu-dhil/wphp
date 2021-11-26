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
class Person {
    public const MALE = 'M';

    public const FEMALE = 'F';

    public const UNKNOWN = 'U';

    public const TRANS = 'T';

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\Column(name="last_name", type="string", length=100, nullable=true)
     */
    private ?string  $lastName = null;

    /**
     * @ORM\Column(name="first_name", type="string", length=100, nullable=true)
     */
    private ?string $firstName = null;

    /**
     * @ORM\Column(name="title", type="string", length=200, nullable=true)
     */
    private ?string  $title = null;

    /**
     * @ORM\Column(name="gender", type="string", length=1, nullable=false, options={"default" = "U"})
     */
    private string $gender;

    /**
     * @ORM\Column(name="dob", type="string", length=20, nullable=true)
     */
    private ?string  $dob = null;

    /**
     * @ORM\Column(name="dod", type="string", length=20, nullable=true)
     */
    private ?string  $dod = null;

    /**
     * @Assert\Url
     * @ORM\Column(name="viaf_permalink", type="string", length=127, nullable=true)
     */
    private ?string  $viafUrl = null;

    /**
     * @Assert\Url
     * @ORM\Column(name="wikipedia_link", type="string", length=127, nullable=true)
     */
    private ?string $wikipediaUrl = null;

    /**
     * @Assert\Url
     * @ORM\Column(name="image_link", type="string", length=255, nullable=true)
     */
    private ?string  $imageUrl = null;

    /**
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private ?string $notes = null;

    /**
     * @ORM\Column(name="finalcheck", type="boolean", nullable=false)
     */
    private bool $finalcheck = false;

    /**
     * @ORM\ManyToOne(targetEntity="Geonames", inversedBy="peopleBorn")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="city_id_of_birth", referencedColumnName="geonameid")
     * })
     */
    private ?Geonames $cityOfBirth = null;

    /**
     * @ORM\ManyToOne(targetEntity="Geonames", inversedBy="peopleDied")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="city_id_of_death", referencedColumnName="geonameid")
     * })
     */
    private ?Geonames $cityOfDeath = null;

    /**
     * @var Collection|TitleRole[]
     * @ORM\OneToMany(targetEntity="TitleRole", mappedBy="person")
     */
    private $titleRoles;

    /**
     * @var Collection|Firm[]
     * @ORM\ManyToMany(targetEntity="Firm", inversedBy="relatedPeople")
     */
    private $relatedFirms;

    /**
     * Construct the person entity.
     */
    public function __construct() {
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

    public function getFormId() {
        return "({$this->id}) " . implode(', ', array_filter([$this->lastName, $this->firstName]));
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function getLastName() : ?string {
        return $this->lastName;
    }

    public function setLastName(?string $lastName) : self {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName() : ?string {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName) : self {
        $this->firstName = $firstName;

        return $this;
    }

    public function getTitle() : ?string {
        return $this->title;
    }

    public function setTitle(?string $title) : self {
        $this->title = $title;

        return $this;
    }

    public function getGender() : ?string {
        return $this->gender;
    }

    public function setGender(string $gender) : self {
        $this->gender = $gender;

        return $this;
    }

    public function getDob() : ?string {
        if ('0000-00-00' === $this->dob) {
            return null;
        }

        return $this->dob;
    }

    public function setDob(?string $dob) : self {
        $this->dob = $dob;

        return $this;
    }

    public function getDod() : ?string {
        if ('0000-00-00' === $this->dod) {
            return null;
        }

        return $this->dod;
    }

    public function setDod(?string $dod) : self {
        $this->dod = $dod;

        return $this;
    }

    public function getViafUrl() : ?string {
        return $this->viafUrl;
    }

    public function setViafUrl(?string $viafUrl) : self {
        $this->viafUrl = $viafUrl;

        return $this;
    }

    public function getWikipediaUrl() : ?string {
        return $this->wikipediaUrl;
    }

    public function setWikipediaUrl(?string $wikipediaUrl) : self {
        $this->wikipediaUrl = $wikipediaUrl;

        return $this;
    }

    public function getImageUrl() : ?string {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl) : self {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getNotes() : ?string {
        return $this->notes;
    }

    public function setNotes(?string $notes) : self {
        $this->notes = $notes;

        return $this;
    }

    public function getFinalcheck() : ?bool {
        return $this->finalcheck;
    }

    public function setFinalcheck(bool $finalcheck) : self {
        $this->finalcheck = $finalcheck;

        return $this;
    }

    public function getCityOfBirth() : ?Geonames {
        return $this->cityOfBirth;
    }

    public function setCityOfBirth(?Geonames $cityOfBirth) : self {
        $this->cityOfBirth = $cityOfBirth;

        return $this;
    }

    public function getCityOfDeath() : ?Geonames {
        return $this->cityOfDeath;
    }

    public function setCityOfDeath(?Geonames $cityOfDeath) : self {
        $this->cityOfDeath = $cityOfDeath;

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
            $titleRole->setPerson($this);
        }

        return $this;
    }

    public function removeTitleRole(TitleRole $titleRole) : self {
        if ($this->titleRoles->removeElement($titleRole)) {
            // set the owning side to null (unless already changed)
            if ($titleRole->getPerson() === $this) {
                $titleRole->setPerson(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Firm[]
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
