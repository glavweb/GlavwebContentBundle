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
use Glavweb\ContentBundle\Event\ContentOption\PostPersistEvent;
use Glavweb\ContentBundle\Event\ContentOption\PostRemoveEvent;
use Glavweb\ContentBundle\Event\ContentOption\PostUpdateEvent;
use Glavweb\ContentBundle\Event\ContentOption\PrePersistEvent;
use Glavweb\ContentBundle\Event\ContentOption\PreRemoveEvent;
use Glavweb\ContentBundle\Event\ContentOption\PreUpdateEvent;
use Glavweb\ContentBundle\ContentOptionEvents;
use Glavweb\ContentBundle\Entity\Option;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class OptionAdmin
 *
 * @package Glavweb\ContentBundle\Admin
 * @author Andrey Nilov <nilov@glavweb.ru>
 */
class OptionAdmin extends AbstractAdmin
{
    /**
     * The base route pattern used to generate the routing information
     *
     * @var string
     */
    protected $baseRoutePattern = 'content-option';

    /**
     * The base route name used to generate the routing information
     *
     * @var string
     */
    protected $baseRouteName = 'admin_content_option';

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->formOptions['translation_domain'] = $this->getTranslationDomain();
    }

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('category')
            ->add('name')
            ->add('value');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('category')
            ->add('name', null, [
                'editable' => true
            ])
            ->add('value', 'text', [
                'editable' => true
            ])
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                )
            ));
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->with('Common', array('class' => 'col-md-6', 'name' => $this->trans('group.label_common')))->end();
        $formMapper->with('Value', array('class' => 'col-md-6', 'name' => $this->trans('group.label_value')))->end();

        $formMapper
            ->with('Common')
                ->add('category')
                ->add('name')
            ->end()

            ->with('Value')
                ->add('value', null, [
                    'label' => false
                ])
            ->end()
        ;
    }

    /**
     * @param object $object
     */
    public function prePersist($object)
    {
        if (!$object instanceof Option) {
            throw new \RuntimeException('The $object must be instance of Option.');
        }

        $this->eventDispatcher()->dispatch(ContentOptionEvents::PRE_PERSIST, new PrePersistEvent($object));
    }

    /**
     * @param object $object
     */
    public function preUpdate($object)
    {
        if (!$object instanceof Option) {
            throw new \RuntimeException('The $object must be instance of Option.');
        }

        $this->eventDispatcher()->dispatch(ContentOptionEvents::PRE_UPDATE, new PreUpdateEvent($object));
    }


    /**
     * @param object $object
     */
    public function postPersist($object)
    {
        if (!$object instanceof Option) {
            throw new \RuntimeException('The $object must be instance of Option.');
        }

        $this->eventDispatcher()->dispatch(ContentOptionEvents::POST_PERSIST, new PostPersistEvent($object));
    }

    /**
     * @param object $object
     */
    public function postUpdate($object)
    {
        if (!$object instanceof Option) {
            throw new \RuntimeException('The $object must be instance of Option.');
        }

        $this->eventDispatcher()->dispatch(ContentOptionEvents::POST_UPDATE, new PostUpdateEvent($object));
    }

    /**
     * @param object $object
     */
    public function preRemove($object)
    {
        if (!$object instanceof Option) {
            throw new \RuntimeException('The $object must be instance of Option.');
        }

        $this->eventDispatcher()->dispatch(ContentOptionEvents::PRE_REMOVE, new PreRemoveEvent($object));
    }

    /**
     * @param object $object
     */
    public function postRemove($object)
    {
        if (!$object instanceof Option) {
            throw new \RuntimeException('The $object must be instance of Option.');
        }

        $this->eventDispatcher()->dispatch(ContentOptionEvents::POST_REMOVE, new PostRemoveEvent($object));
    }

    /**
     * @return object|\Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher|\Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher
     */
    protected function eventDispatcher()
    {
        return $this->getContainer()->get('event_dispatcher');
    }
}
