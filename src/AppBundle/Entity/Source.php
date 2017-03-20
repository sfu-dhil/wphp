<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Source
 *
 * @ORM\Table(name="source")
 * @ORM\Entity
 */
class Source
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", nullable=true)
     */
    private $name;

    /**
     * @var boolean
     * @ORM\Column(name="local", type="boolean", nullable=false, options={"default" : 0})
     */
    private $local;

    /**
     * @var string
     * @ORM\Column(name="url", type="string", length=60, nullable=true)
     */
    private $url;
    
    /**
     * @var string
     * @ORM\Column(name="sourcetable", type="string", length=60, nullable=true)
     */
    private $sourceTable;

    /**
     * Get id
     *
     * @return boolean
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Source
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set local
     *
     * @param boolean $local
     *
     * @return Source
     */
    public function setLocal($local)
    {
        $this->local = $local;

        return $this;
    }

    /**
     * Get local
     *
     * @return boolean
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Source
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set sourceTable
     *
     * @param string $sourceTable
     *
     * @return Source
     */
    public function setSourceTable($sourceTable)
    {
        $this->sourceTable = $sourceTable;

        return $this;
    }

    /**
     * Get sourceTable
     *
     * @return string
     */
    public function getSourceTable()
    {
        return $this->sourceTable;
    }
}
