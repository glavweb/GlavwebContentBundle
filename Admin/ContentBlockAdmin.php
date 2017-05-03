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
use Glavweb\ContentBundle\Entity\ContentBlock;

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
    protected $baseRouteName = 'content_block';

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
            ->add('body')
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
        $formMapper->with('Common', array('class' => 'col-md-6', 'name' => $this->trans('group.label_block')))->end();
        $formMapper->with('Attributes', array('class' => 'col-md-6', 'name' => $this->trans('group.label_attributes')))->end();

        $contentOnly = $this->getRequest()->get('content_only');

        if (!$contentOnly) {
            $formMapper
                ->with('Common')
                    ->add('category')
                    ->add('name')
                ->end()
            ;
        }

        $formMapper->with('Common')
            ->add('body', null, [
                'attr' => ['rows' => 6]
            ])
        ->end();

        $formMapper->with('Attributes')
            ->add('attributes', 'sonata_type_collection',
                array(
                    'required'     => false,
                    'by_reference' => false,
                    'label'        => false,
                    'type_options' => array(
                        'delete'   => true,
                        'required' => true,
                    ),
                ),
                array(
                    'edit'         => 'inline',
                    'inline'       => 'table',
                    'allow_delete' => true,
                )
            )
        ->end();
    }
}
