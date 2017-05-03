<?php

/*
 * This file is part of the "GlavwebContentBlockAdmin" package.
 *
 * (c) GLAVWEB <info@glavweb.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glavweb\ContentBundle\Admin;

use Glavweb\CmsCoreBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Glavweb\ContentBundle\Entity\ContentBlockAttribute;

/**
 * Class ContentBlockAttributeAdmin
 *
 * @package Glavweb\ContentBundle\Admin
 * @author Andrey Nilov <nilov@glavweb.ru>
 */
class ContentBlockAttributeAdmin extends AbstractAdmin
{
    /**
     * The base route pattern used to generate the routing information
     *
     * @var string
     */
    protected $baseRoutePattern = 'content-block-attribute';

    /**
     * The base route name used to generate the routing information
     *
     * @var string
     */
    protected $baseRouteName = 'content_block_attribute';

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
            ->add('name')
            ->add('body')
            ->add('contentBlock')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('body')
            ->add('contentBlock')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var ContentBlockAttribute $contentBlockAttribute */
        // $contentBlockAttribute = $this->getSubject();
        // $container = $this->getConfigurationPool()->getContainer();
        // $formMapper->with('Common', array('class' => 'col-md-6', 'name' => 'Common'))->end();
        // $formMapper->with('Collection', array('class' => 'col-md-6', 'name' => 'Collection'))->end();

        $formMapper
            ->add('name')
            ->add('body')
        ;
    }

    /**
     * @param mixed $contentBlockAttribute
     */
    public function prePersist($contentBlockAttribute)
    {}

    /**
     * @param mixed $contentBlockAttribute
     */
    public function preUpdate($contentBlockAttribute)
    {}
}
