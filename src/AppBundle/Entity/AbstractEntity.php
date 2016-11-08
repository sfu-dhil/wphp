<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractEntity implements JsonSerializable
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    public function getId() {
        return $this->id;
    }

    private function setCreated() {
        // nop
    }

    /**
     * @return DateTime
     */
    public function getCreated() {
        return $this->created;
    }

    private function setUpdated() {
        // nop
    }

    /**
     * @return DateTime
     */
    public function getUpdated() {
        return $this->updated;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist() {
        $this->created = new DateTime();
        $this->updated = new DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate() {
        $this->updated = new DateTime();
    }

    abstract public function __toString();

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'created' => $this->created->format('c'),
            'updated' => $this->updated->format('c'),
        );
    }

}
