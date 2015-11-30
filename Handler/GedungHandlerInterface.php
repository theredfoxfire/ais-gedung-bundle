<?php

namespace Ais\GedungBundle\Handler;

use Ais\GedungBundle\Model\GedungInterface;

interface GedungHandlerInterface
{
    /**
     * Get a Gedung given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return GedungInterface
     */
    public function get($id);

    /**
     * Get a list of Gedungs.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

    /**
     * Post Gedung, creates a new Gedung.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return GedungInterface
     */
    public function post(array $parameters);

    /**
     * Edit a Gedung.
     *
     * @api
     *
     * @param GedungInterface   $gedung
     * @param array           $parameters
     *
     * @return GedungInterface
     */
    public function put(GedungInterface $gedung, array $parameters);

    /**
     * Partially update a Gedung.
     *
     * @api
     *
     * @param GedungInterface   $gedung
     * @param array           $parameters
     *
     * @return GedungInterface
     */
    public function patch(GedungInterface $gedung, array $parameters);
}
