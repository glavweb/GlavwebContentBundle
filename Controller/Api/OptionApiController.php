<?php

/*
 * This file is part of the "GlavwebContentBlockAdmin" package.
 *
 * (c) GLAVWEB <info@glavweb.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glavweb\ContentBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Glavweb\RestBundle\Controller\GlavwebRestController;
use Glavweb\RestBundle\Mapping\Annotation as RestExtra;
use Glavweb\RestBundle\Scope\ScopeFetcherInterface;
use Glavweb\DatagridBundle\Filter\Doctrine\Filter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Glavweb\ContentBundle\Entity\Option;
use Glavweb\ContentBundle\Form\OptionType as OptionFormType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class OptionApiController
 *
 * @package Glavweb\ContentBundle\Controller\Api
 * @author Andrey Nilov <nilov@glavweb.ru>
 */
class OptionApiController extends GlavwebRestController
{
    /**
     * Returns collection of options
     *
     * @ApiDoc(
     *     views={"default", "option"},
     *     section="Option API",
     *     statusCodes={
     *         200="Returned when successful",
     *         206="Returned when successful",
     *         400="Returned when an error has occurred"
     *     },
     *     responseMap={
     *         200 = {"class": null, "options": {"data_schema": "option.schema.yml"}}
     *     }
     * )
     *
     * @Route("options", name="api_option_get_options", requirements={
     *     "_scope":  "[\w,]+",
     *     "_oprs":   "\d+",
     *     "_sort":   "ASC|DESC",
     *     "_offset": "\d+",
     *     "_limit":  "\d+"
     * }, defaults={"_format": "json"}, methods={"GET"})
     *
     * @RestExtra\Scope(name="list", path="option/list.yml")
     *
     * @Rest\QueryParam(name="category", nullable=true, description="Category")
     * @Rest\QueryParam(name="name", nullable=true, description="Name")
     * @Rest\QueryParam(name="value", nullable=true, description="Value")
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param ScopeFetcherInterface $scopeFetcher
     * @param Request $request
     * @return Option[]
     */
    public function getOptionsAction(ParamFetcherInterface $paramFetcher, ScopeFetcherInterface $scopeFetcher, Request $request)
    {
        // Define datagrid builder
        $datagridBuilder = $this->get('glavweb_datagrid.doctrine_datagrid_builder');
        $datagridBuilder
            ->setEntityClassName(Option::class)
            ->setFirstResult($request->get('_offset'))
            ->setMaxResults(min($request->get('_limit', 100), 1000))
            ->setOrderings($request->get('_sort'))
            ->setOperators($request->get('_oprs', []))
            ->setDataSchema('option.schema.yml', $scopeFetcher->getAvailable($request->get('_scope'), 'option/list.yml'))
        ;

        // Define filters
        $datagridBuilder
            ->addFilter('category')
            ->addFilter('name')
            ->addFilter('value')
        ;

        $datagrid = $datagridBuilder->build($paramFetcher->all());

        return $this->createListViewByDatagrid($datagrid);
    }

    /**
     * Returns option
     *
     * @ApiDoc(
     *     views={"default", "option"},
     *     section="Option API",
     *     statusCodes={
     *         200="Returned when successful",
     *         400="Returned when an error has occurred"
     *     },
     *     responseMap={
     *         200 = {"class": null, "options": {"data_schema": "option.schema.yml"}}
     *     }
     * )
     *
     * @Route("options/{option}", name="api_option_get_option", requirements={
     *     "_scope":  "[\w,]+",
     *     "option":  "[\d]+"
     * }, defaults={"_format": "json"}, methods={"GET"})
     *
     * @RestExtra\Scope(name="view", path="option/view.yml")
     *
     * @param Option $option
     * @param ScopeFetcherInterface $scopeFetcher
     * @param Request $request
     * @return View
     */
    public function getOptionAction(Option $option, ScopeFetcherInterface $scopeFetcher, Request $request)
    {
        $datagridBuilder = $this->get('glavweb_datagrid.doctrine_datagrid_builder');
        $datagridBuilder
            ->setEntityClassName(Option::class)
            ->setDataSchema('option.schema.yml', $scopeFetcher->getAvailable($request->get('_scope'), 'option/view.yml'))
            ->addFilter('id', null, ['operator' => Filter::EQ])
        ;

        $datagrid = $datagridBuilder->build(['id' => $option->getId()]);

        return $this->view($datagrid->getItem());
    }

    /**
     * Returns option ID
     *
     * @ApiDoc(
     *     views={"default", "option"},
     *     section="Option API",
     *     statusCodes={
     *         200="Returned when successful",
     *         400="Returned when an error has occurred"
     *     }
     * )
     *
     * @Security("is_granted('ROLE_API')")
     *
     * @Route("options/id", name="api_option_get_option_id", defaults={"_format": "json"}, methods={"GET"})
     *
     * @Rest\QueryParam(name="category", nullable=true, description="Category")
     * @Rest\QueryParam(name="name", nullable=true, description="Name")
     *
     * @param ParamFetcherInterface $paramFetcher
     * @return Response
     */
    public function getIdAction(ParamFetcherInterface $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $paramFetcher->get('category');
        $name     = $paramFetcher->get('name');

        $option = null;
        if ($category && $name) {
            $option = $em->getRepository(Option::class)->findOneBy([
                'category' => $category,
                'name'     => $name
            ]);
        }

        if (!$option) {
            throw $this->createNotFoundException();
        }

        return [
            'id' => $option->getId()
        ];
    }

    /**
     * Create option
     *
     * @ApiDoc(
     *     views={"default", "option"},
     *     section="Option API",
     *     input={"class"="Glavweb\ContentBundle\Form\OptionType", "name"=""},
     *     statusCodes={
     *         201="Returned when successful",
     *         400="Returned when an error has occurred",
     *     }
     * )
     *
     * @Security("is_granted('ROLE_API')")
     *
     * @Route("options", name="api_option_create_option", defaults={"_format": "json"}, methods={"POST"})
     *
     * @param Request $request A Symfony request
     * @return FormInterface|Response
     */
    public function createOptionAction(Request $request)
    {
        $formType = new OptionFormType();
        $option = new Option();

        $restFormAction = $this->get('glavweb_rest.form_action');
        $actionResponse = $restFormAction->execute(array(
            'request'   => $request,
            'formType'  => $formType,
            'entity'    => $option,
            'onSuccess' => function($request, $form, Option $option, $response) {
                $response->headers->set('Location',
                    $this->generateUrl(
                        'api_option_get_option',
                        array('option' => $option->getId()),
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                );
            }
        ));

        return $actionResponse->response;
    }

    /**
     * Update option
     *
     * @ApiDoc(
     *     views={"default", "option"},
     *     section="Option API",
     *     input={"class"="Glavweb\ContentBundle\Form\OptionType", "name"=""},
     *     statusCodes={
     *         200="Returned when successful",
     *         204="Returned when successful",
     *         400="Returned when an error has occurred",
     *     }
     * )
     *
     * @Security("is_granted('ROLE_MODERATOR')")
     *
     * @Route("options", name="api_option_put_option", defaults={"_format": "json"}, methods={"PUT"})
     * @Route("options", name="api_option_patch_option", defaults={"_format": "json", "isPatch": true}, methods={"PATCH"})
     *
     * @param Request $request
     * @param bool    $isPatch
     * @return FormInterface|Response
     */
    public function updateOptionAction(Request $request, $isPatch = false)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $request->get('category');
        $name     = $request->get('name');

        $option = null;
        if ($category && $name) {
            $option = $em->getRepository(Option::class)->findOneBy([
                'category' => $category,
                'name'     => $name
            ]);
        }

        if (!$option) {
            throw $this->createNotFoundException();
        }

        $formType = new OptionFormType();

        $restFormAction = $this->get('glavweb_rest.form_action');
        $actionResponse = $restFormAction->execute(array(
            'request'    => $request,
            'formType'   => $formType,
            'entity'     => $option,
            'cleanForm'  => $isPatch
        ));

        return $actionResponse->response;
    }

    /**
     * Delete option
     *
     * @ApiDoc(
     *     views={"default", "option"},
     *     section="Option API",
     *     statusCodes={
     *         201="Returned when successful",
     *         400="Returned when an error has occurred",
     *     }
     * )
     *
     * @Security("is_granted('ROLE_API', option)")
     *
     * @Route("options/{option}", name="api_option_delete_option", defaults={"_format": "json"}, methods={"DELETE"})
     *
     * @param Option $option
     * @return Response
     */
    public function deleteOptionAction(Option $option)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($option);
        $em->flush();

        return new Response('', 204);
    }
}