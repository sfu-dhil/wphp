<?php

/**
 * @file
 * 
 * A useful base class for Doctrine entities.
 */

namespace Nines\UtilBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * AbstractEntity adds id, created, and updated fields along with the
 * normal getters. And it sets up automatic callbacks to set the created
 * and updated DateTimes.
 * 
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractEntity
{

    /**
     * The entity's ID.
     * 
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The DateTime the entity was created (persisted really).
     * 
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * The DateTime the entity was last updated.
     * 
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * Constructor. Does nothing. Exists incase a subclass accidentally calls
     * parent::__construct().
     */
    public function __construct() {
        // nop
    }
    
    /**
     * Get the ID.
     * 
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Does nothing. Setting the created timestamp happens automatically. Exists
     * to prevent a subclass accien
     */
    final private function setCreated() {
        // nop
    }

    /**
     * Get the created timestamp.
     * 
     * @return DateTime
     */
    final public function getCreated() {
        return $this->created;
    }

    /**
     * Does nothing. Setting the updated timestamp happens automatically.
     */
    final private function setUpdated() {
        // nop
    }

    /**
     * Get the updated timestamp.
     * 
     * @return DateTime
     */
    public function getUpdated() {
        return $this->updated;
    }

    /**
     * Sets the created and updated timestamps.
     * 
     * @ORM\PrePersist()
     */
    final public function prePersist() {
        if(! isset($this->created)) {
            $this->created = new DateTime();
            $this->updated = new DateTime();
        }
    }

    /**
     * Sets the updated timestamp.
     * 
     * @ORM\PreUpdate()
     */
    final public function preUpdate() {
        $this->updated = new DateTime();
    }

    /**
     * Force all entities to provide a stringify function.
     */
    abstract public function __toString();
}
