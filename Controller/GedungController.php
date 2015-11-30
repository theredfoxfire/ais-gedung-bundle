<?php

namespace Ais\GedungBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Symfony\Component\Form\FormTypeInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Ais\GedungBundle\Exception\InvalidFormException;
use Ais\GedungBundle\Form\GedungType;
use Ais\GedungBundle\Model\GedungInterface;


class GedungController extends FOSRestController
{
    /**
     * List all gedungs.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing gedungs.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many gedungs to return.")
     *
     * @Annotations\View(
     *  templateVar="gedungs"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getGedungsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('ais_gedung.gedung.handler')->all($limit, $offset);
    }

    /**
     * Get single Gedung.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Gedung for a given id",
     *   output = "Ais\GedungBundle\Entity\Gedung",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the gedung is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="gedung")
     *
     * @param int     $id      the gedung id
     *
     * @return array
     *
     * @throws NotFoundHttpException when gedung not exist
     */
    public function getGedungAction($id)
    {
        $gedung = $this->getOr404($id);

        return $gedung;
    }

    /**
     * Presents the form to use to create a new gedung.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  templateVar = "form"
     * )
     *
     * @return FormTypeInterface
     */
    public function newGedungAction()
    {
        return $this->createForm(new GedungType());
    }
    
    /**
     * Presents the form to use to edit gedung.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisGedungBundle:Gedung:editGedung.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the gedung id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when gedung not exist
     */
    public function editGedungAction($id)
    {
		$gedung = $this->getGedungAction($id);
		
        return array('form' => $this->createForm(new GedungType(), $gedung), 'gedung' => $gedung);
    }

    /**
     * Create a Gedung from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new gedung from the submitted data.",
     *   input = "Ais\GedungBundle\Form\GedungType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisGedungBundle:Gedung:newGedung.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postGedungAction(Request $request)
    {
        try {
            $newGedung = $this->container->get('ais_gedung.gedung.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $newGedung->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_gedung', $routeOptions, Codes::HTTP_CREATED);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing gedung from the submitted data or create a new gedung at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Ais\GedungBundle\Form\GedungType",
     *   statusCodes = {
     *     201 = "Returned when the Gedung is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisGedungBundle:Gedung:editGedung.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the gedung id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when gedung not exist
     */
    public function putGedungAction(Request $request, $id)
    {
        try {
            if (!($gedung = $this->container->get('ais_gedung.gedung.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $gedung = $this->container->get('ais_gedung.gedung.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $gedung = $this->container->get('ais_gedung.gedung.handler')->put(
                    $gedung,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $gedung->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_gedung', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing gedung from the submitted data or create a new gedung at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Ais\GedungBundle\Form\GedungType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisGedungBundle:Gedung:editGedung.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the gedung id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when gedung not exist
     */
    public function patchGedungAction(Request $request, $id)
    {
        try {
            $gedung = $this->container->get('ais_gedung.gedung.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $gedung->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_gedung', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Fetch a Gedung or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return GedungInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($gedung = $this->container->get('ais_gedung.gedung.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        return $gedung;
    }
    
    public function postUpdateGedungAction(Request $request, $id)
    {
		try {
            $gedung = $this->container->get('ais_gedung.gedung.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $gedung->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_gedung', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
	}
}
