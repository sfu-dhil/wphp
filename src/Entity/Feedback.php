<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Feedback.
 *
 * @ORM\Table(name="feedback")
 * @ORM\Entity(repositoryClass="App\Repository\FeedbackRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Feedback
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Email(
     *     message="The email '{{ value }}' is not a valid email."
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * The DateTime the entity was created (persisted really).
     *
     * @var DateTime
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * Does nothing.
     */
    private function setCreated() : void {
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
     * Set name.
     *
     * @param string $name
     *
     * @return Feedback
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return Feedback
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Feedback
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Sets the created timestamps.
     *
     * @ORM\PrePersist
     */
    public function prePersist() : void {
        if ( ! isset($this->created)) {
            $this->created = new DateTimeImmutable();
        }
    }

    /**
     * Get the created timestamp.
     */
    public function getCreated() {
        return $this->created;
    }
}
