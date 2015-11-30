<?php

namespace Ais\GedungBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ais\GedungBundle\Model\GedungInterface;

/**
 * Gedung
 */
class Gedung implements GedungInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $kode;

    /**
     * @var string
     */
    private $nama;

    /**
     * @var boolean
     */
    private $is_active;

    /**
     * @var boolean
     */
    private $is_delete;


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
     * Set kode
     *
     * @param string $kode
     *
     * @return Gedung
     */
    public function setKode($kode)
    {
        $this->kode = $kode;

        return $this;
    }

    /**
     * Get kode
     *
     * @return string
     */
    public function getKode()
    {
        return $this->kode;
    }

    /**
     * Set nama
     *
     * @param string $nama
     *
     * @return Gedung
     */
    public function setNama($nama)
    {
        $this->nama = $nama;

        return $this;
    }

    /**
     * Get nama
     *
     * @return string
     */
    public function getNama()
    {
        return $this->nama;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Gedung
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set isDelete
     *
     * @param boolean $isDelete
     *
     * @return Gedung
     */
    public function setIsDelete($isDelete)
    {
        $this->is_delete = $isDelete;

        return $this;
    }

    /**
     * Get isDelete
     *
     * @return boolean
     */
    public function getIsDelete()
    {
        return $this->is_delete;
    }
}

