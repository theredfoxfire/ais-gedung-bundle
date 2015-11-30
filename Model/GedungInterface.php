<?php

namespace Ais\GedungBundle\Model;

Interface GedungInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set kode
     *
     * @param string $kode
     *
     * @return Gedung
     */
    public function setKode($kode);

    /**
     * Get kode
     *
     * @return string
     */
    public function getKode();

    /**
     * Set nama
     *
     * @param string $nama
     *
     * @return Gedung
     */
    public function setNama($nama);

    /**
     * Get nama
     *
     * @return string
     */
    public function getNama();

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Gedung
     */
    public function setIsActive($isActive);

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive();

    /**
     * Set isDelete
     *
     * @param boolean $isDelete
     *
     * @return Gedung
     */
    public function setIsDelete($isDelete);

    /**
     * Get isDelete
     *
     * @return boolean
     */
    public function getIsDelete();
}
