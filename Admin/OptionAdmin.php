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
use Glavweb\ContentBundle\Entity\Option;

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
}
