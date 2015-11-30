<?php

namespace Ais\GedungBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Ais\GedungBundle\Model\GedungInterface;
use Ais\GedungBundle\Form\GedungType;
use Ais\GedungBundle\Exception\InvalidFormException;

class GedungHandler implements GedungHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }

    /**
     * Get a Gedung.
     *
     * @param mixed $id
     *
     * @return GedungInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Gedungs.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    /**
     * Create a new Gedung.
     *
     * @param array $parameters
     *
     * @return GedungInterface
     */
    public function post(array $parameters)
    {
        $gedung = $this->createGedung();

        return $this->processForm($gedung, $parameters, 'POST');
    }

    /**
     * Edit a Gedung.
     *
     * @param GedungInterface $gedung
     * @param array         $parameters
     *
     * @return GedungInterface
     */
    public function put(GedungInterface $gedung, array $parameters)
    {
        return $this->processForm($gedung, $parameters, 'PUT');
    }

    /**
     * Partially update a Gedung.
     *
     * @param GedungInterface $gedung
     * @param array         $parameters
     *
     * @return GedungInterface
     */
    public function patch(GedungInterface $gedung, array $parameters)
    {
        return $this->processForm($gedung, $parameters, 'PATCH');
    }

    /**
     * Processes the form.
     *
     * @param GedungInterface $gedung
     * @param array         $parameters
     * @param String        $method
     *
     * @return GedungInterface
     *
     * @throws \Ais\GedungBundle\Exception\InvalidFormException
     */
    private function processForm(GedungInterface $gedung, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new GedungType(), $gedung, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $gedung = $form->getData();
            $this->om->persist($gedung);
            $this->om->flush($gedung);

            return $gedung;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createGedung()
    {
        return new $this->entityClass();
    }

}
