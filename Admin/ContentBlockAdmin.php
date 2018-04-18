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
}
