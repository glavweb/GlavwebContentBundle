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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
use Glavweb\ContentBundle\Entity\ContentBlock;
use Glavweb\ContentBundle\Form\ContentBlockType as ContentBlockFormType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ContentBlockApiController
 *
 * @package Glavweb\ContentBundle\Controller\Api
 * @author Andrey Nilov <nilov@glavweb.ru>
 */
class ContentBlockApiController extends GlavwebRestController
{
    /**
     * Returns collection of content blocks
     *
     * @ApiDoc(
     *     views={"default", "content-block"},
     *     section="ContentBlock API",
     *     statusCodes={
     *         200="Returned when successful",
     *         206="Returned when successful",
     *         400="Returned when an error has occurred"
     *     },
     *     responseMap={
     *         200 = {"class": null, "options": {"data_schema": "content_block.schema.yml"}}
     *     }
     * )
     *
     * @Route("content-blocks", name="api_content_block_get_content_blocks", requirements={
     *     "_scope":  "[\w,]+",
     *     "_oprs":   "\d+",
     *     "_sort":   "ASC|DESC",
     *     "_offset": "\d+",
     *     "_limit":  "\d+"
     * }, defaults={"_format": "json"}, methods={"GET"})
     *
     * @RestExtra\Scope(name="list", path="content_block/list.yml")
     *
     * @Rest\QueryParam(name="category", nullable=true, description="Category")
     * @Rest\QueryParam(name="name", nullable=true, description="Name")
     * @Rest\QueryParam(name="body", nullable=true, description="Body")
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param ScopeFetcherInterface $scopeFetcher
     * @param Request $request
     * @return ContentBlock[]
     */
    public function getContentBlocksAction(ParamFetcherInterface $paramFetcher, ScopeFetcherInterface $scopeFetcher, Request $request)
    {
        // Define datagrid builder
        $datagridBuilder = $this->get('glavweb_datagrid.doctrine_datagrid_builder');
        $datagridBuilder
            ->setEntityClassName(ContentBlock::class)
            ->setFirstResult($request->get('_offset'))
            ->setMaxResults(min($request->get('_limit', 100), 1000))
            ->setOrderings($request->get('_sort'))
            ->setOperators($request->get('_oprs', []))
            ->setDataSchema('content_block.schema.yml', $scopeFetcher->getAvailable($request->get('_scope'), 'content_block/list.yml'))
        ;

        // Define filters
        $datagridBuilder
            ->addFilter('category')
            ->addFilter('name')
            ->addFilter('body')
        ;

        $datagrid = $datagridBuilder->build($paramFetcher->all());

        return $this->createListViewByDatagrid($datagrid);
    }

    /**
     * Returns content block
     *
     * @ApiDoc(
     *     views={"default", "content-block"},
     *     section="ContentBlock API",
     *     statusCodes={
     *         200="Returned when successful",
     *         400="Returned when an error has occurred"
     *     },
     *     responseMap={
     *         200 = {"class": null, "options": {"data_schema": "content_block.schema.yml"}}
     *     }
     * )
     *
     * @Route("content-blocks/{contentBlock}", name="api_content_block_get_content_block", requirements={
     *     "_scope":  "[\w,]+",
     *     "contentBlock":  "[\d]+"
     * }, defaults={"_format": "json"}, methods={"GET"})
     *
     * @RestExtra\Scope(name="view", path="content_block/view.yml")
     *
     * @param ContentBlock $contentBlock
     * @param ScopeFetcherInterface $scopeFetcher
     * @param Request $request
     * @return View
     */
    public function getContentBlockAction(ContentBlock $contentBlock, ScopeFetcherInterface $scopeFetcher, Request $request)
    {
        $datagridBuilder = $this->get('glavweb_datagrid.doctrine_datagrid_builder');
        $datagridBuilder
            ->setEntityClassName(ContentBlock::class)
            ->setDataSchema('content_block.schema.yml', $scopeFetcher->getAvailable($request->get('_scope'), 'content_block/view.yml'))
            ->addFilter('id', null, ['operator' => Filter::EQ])
        ;

        $datagrid = $datagridBuilder->build(['id' => $contentBlock->getId()]);

        return $this->view($datagrid->getItem());
    }

    /**
     * Returns content block ID
     *
     * @ApiDoc(
     *     views={"default", "content-block"},
     *     section="ContentBlock API",
     *     statusCodes={
     *         200="Returned when successful",
     *         400="Returned when an error has occurred"
     *     }
     * )
     *
     * @Security("is_granted('ROLE_API')")
     *
     * @Route("content-blocks/id", name="api_content_block_get_content_block_id", defaults={"_format": "json"}, methods={"GET"})
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

        $contentBlock = null;
        if ($category && $name) {
            $contentBlock = $em->getRepository(ContentBlock::class)->findOneBy([
                'category' => $category,
                'name'     => $name
            ]);
        }

        if (!$contentBlock) {
            throw $this->createNotFoundException();
        }

        return [
            'id' => $contentBlock->getId()
        ];
    }

    /**
     * Create content block
     *
     * @ApiDoc(
     *     views={"default", "content-block"},
     *     section="ContentBlock API",
     *     input={"class"="Glavweb\ContentBundle\Form\ContentBlockType", "name"=""},
     *     statusCodes={
     *         201="Returned when successful",
     *         400="Returned when an error has occurred",
     *     }
     * )
     *
     * @Security("is_granted('ROLE_API')")
     *
     * @Route("content-blocks", name="api_content_block_create_content_block", defaults={"_format": "json"}, methods={"POST"})
     *
     * @param Request $request A Symfony request
     * @return FormInterface|Response
     */
    public function createContentBlockAction(Request $request)
    {
        $formType = new ContentBlockFormType();
        $contentBlock = new ContentBlock();

        $restFormAction = $this->get('glavweb_rest.form_action');
        $actionResponse = $restFormAction->execute(array(
            'request'   => $request,
            'formType'  => $formType,
            'entity'    => $contentBlock,
            'onSuccess' => function($request, $form, ContentBlock $contentBlock, $response) {
                $response->headers->set('Location',
                    $this->generateUrl(
                        'api_content_block_get_content_block',
                        array('contentBlock' => $contentBlock->getId()),
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                );
            }
        ));

        return $actionResponse->response;
    }

    /**
     * Update content block
     *
     * @ApiDoc(
     *     views={"default", "content-block"},
     *     section="ContentBlock API",
     *     input={"class"="Glavweb\ContentBundle\Form\ContentBlockType", "name"=""},
     *     statusCodes={
     *         200="Returned when successful",
     *         204="Returned when successful",
     *         400="Returned when an error has occurred",
     *     }
     * )
     *
     * @Security("is_granted('ROLE_MODERATOR')")
     *
     * @Route("content-blocks", name="api_content_block_put_content_block", defaults={"_format": "json"}, methods={"PUT"})
     * @Route("content-blocks", name="api_content_block_patch_content_block", defaults={"_format": "json", "isPatch": true}, methods={"PATCH"})
     *
     * @param Request $request
     * @param bool    $isPatch
     * @return FormInterface|Response
     */
    public function updateContentBlockAction(Request $request, $isPatch = false)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $request->get('category');
        $name     = $request->get('name');

        $contentBlock = null;
        if ($category && $name) {
            $contentBlock = $em->getRepository(ContentBlock::class)->findOneBy([
                'category' => $category,
                'name'     => $name
            ]);
        }

        if (!$contentBlock) {
            throw $this->createNotFoundException();
        }

        $formType = new ContentBlockFormType();

        $restFormAction = $this->get('glavweb_rest.form_action');
        $actionResponse = $restFormAction->execute(array(
            'request'    => $request,
            'formType'   => $formType,
            'entity'     => $contentBlock,
            'cleanForm'  => $isPatch
        ));

        return $actionResponse->response;
    }

    /**
     * Delete content block
     *
     * @ApiDoc(
     *     views={"default", "content-block"},
     *     section="ContentBlock API",
     *     statusCodes={
     *         201="Returned when successful",
     *         400="Returned when an error has occurred",
     *     }
     * )
     *
     * @Security("is_granted('ROLE_API', contentBlock)")
     *
     * @Route("content-blocks/{contentBlock}", name="api_content_block_delete_content_block", defaults={"_format": "json"}, methods={"DELETE"})
     *
     * @param ContentBlock $contentBlock
     * @return Response
     */
    public function deleteContentBlockAction(ContentBlock $contentBlock)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($contentBlock);
        $em->flush();

        return new Response('', 204);
    }
}