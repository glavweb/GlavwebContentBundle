<?php

/*
 * This file is part of the "GlavwebContentBundle" package.
 *
 * (c) GLAVWEB <info@glavweb.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glavweb\ContentBundle\Admin;

use Glavweb\CmsCoreBundle\Admin\AbstractAdmin;
use Glavweb\ContentBundle\Event\ContentBlock\PostPersistEvent;
use Glavweb\ContentBundle\Event\ContentBlock\PostRemoveEvent;
use Glavweb\ContentBundle\Event\ContentBlock\PostUpdateEvent;
use Glavweb\ContentBundle\Event\ContentBlock\PrePersistEvent;
use Glavweb\ContentBundle\Event\ContentBlock\PreRemoveEvent;
use Glavweb\ContentBundle\Event\ContentBlock\PreUpdateEvent;
use Glavweb\ContentBundle\ContentBlockEvents;
use Glavweb\ContentBundle\Entity\ContentBlock;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Class ContentBlockAdmin
 *
 * @package Glavweb\ContentBundle\Admin
 * @author Andrey Nilov <nilov@glavweb.ru>
 */
class ContentBlockAdmin extends AbstractAdmin
{
    /**
     * The base route pattern used to generate the routing information
     *
     * @var string
     */
    protected $baseRoutePattern = 'content-block';

    /**
     * The base route name used to generate the routing information
     *
     * @var string
     */
    protected $baseRouteName = 'admin_content_block';

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->formOptions['translation_domain'] = $this->getTranslationDomain();

        $this->setTemplate('list', 'GlavwebContentBundle:admin/content_block:list.html.twig');
    }

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
    }

    /**
     * @return array
     */
    public function getPersistentParameters()
    {
        $request = $this->getRequest();

        return [
            'content_only' => $request->get('content_only')
        ];
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('category')
            ->add('name')
            ->add('body')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('category')
            ->add('name')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ]
            ])
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->with('default', ['class' => 'col-md-7 header-hidden', 'label' => ''])->end();
        $formMapper->with('attributes', ['class' => 'col-md-5', 'label' => $this->trans('group.label_attributes')])->end();

        /** @var ContentBlock $contentBlock */
        $contentBlock = $this->getSubject();

        $contentOnly = $this->getRequest()->get('content_only');
        $bodyType = $contentBlock->getWysiwyg() ? CKEditorType::class : TextareaType::class;

        if (!$contentOnly) {
            $formMapper
                ->with('default')
                    ->add('category')
                    ->add('name')
                    ->add('wysiwyg')
                ->end()
            ;
        }

        $formMapper->with('default')
            ->add('body', $bodyType, [
                'attr' => ['rows' => 6]
            ])
        ->end();

        $formMapper->with('attributes')
            ->add('attributes', 'sonata_type_collection',
                [
                    'required'     => false,
                    'by_reference' => false,
                    'label'        => false,
                    'type_options' => [
                        'delete'   => true,
                        'required' => true,
                    ],
                ],
                [
                    'edit'         => 'inline',
                    'inline'       => 'table',
                    'allow_delete' => true,
                ]
            )
        ->end();
    }

    /**
     * @param object $object
     */
    public function prePersist($object)
    {
        if (!$object instanceof ContentBlock) {
            throw new \RuntimeException('The $object must be instance of ContentBlock.');
        }

        $this->eventDispatcher()->dispatch(ContentBlockEvents::PRE_PERSIST, new PrePersistEvent($object));
    }

    /**
     * @param object $object
     */
    public function preUpdate($object)
    {
        if (!$object instanceof ContentBlock) {
            throw new \RuntimeException('The $object must be instance of ContentBlock.');
        }

        $this->eventDispatcher()->dispatch(ContentBlockEvents::PRE_UPDATE, new PreUpdateEvent($object));
    }


    /**
     * @param object $object
     */
    public function postPersist($object)
    {
        if (!$object instanceof ContentBlock) {
            throw new \RuntimeException('The $object must be instance of ContentBlock.');
        }

        $this->eventDispatcher()->dispatch(ContentBlockEvents::POST_PERSIST, new PostPersistEvent($object));
    }

    /**
     * @param object $object
     */
    public function postUpdate($object)
    {
        if (!$object instanceof ContentBlock) {
            throw new \RuntimeException('The $object must be instance of ContentBlock.');
        }

        $this->eventDispatcher()->dispatch(ContentBlockEvents::POST_UPDATE, new PostUpdateEvent($object));
    }

    /**
     * @param object $object
     */
    public function preRemove($object)
    {
        if (!$object instanceof ContentBlock) {
            throw new \RuntimeException('The $object must be instance of ContentBlock.');
        }

        $this->eventDispatcher()->dispatch(ContentBlockEvents::PRE_REMOVE, new PreRemoveEvent($object));
    }

    /**
     * @param object $object
     */
    public function postRemove($object)
    {
        if (!$object instanceof ContentBlock) {
            throw new \RuntimeException('The $object must be instance of ContentBlock.');
        }

        $this->eventDispatcher()->dispatch(ContentBlockEvents::POST_REMOVE, new PostRemoveEvent($object));
    }

    /**
     * @return object|\Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher|\Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher
     */
    protected function eventDispatcher()
    {
        return $this->getContainer()->get('event_dispatcher');
    }
}
