<?php

/*
 * This file is part of the "GlavwebContentBlockAdmin" package.
 *
 * (c) GLAVWEB <info@glavweb.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glavweb\ContentBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Class DataTransformerPass
 *
 * Adds tagged glavweb_datagrid.data_transformer services to DataTransformerRegister service.
 *
 * @package Glavweb\ContentBundle\DependencyInjection\Compiler
 * @author Andrey Nilov <nilov@glavweb.ru>
 */
class DataTransformerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
//        $em = $container->get('doctrine')->getManager();

//        $definition = new Definition('Glavweb\ContentBundle\Admin\OptionAdmin', [
//            null,
//            'Glavweb\ContentBundle\Entity\Option',
//            'GlavwebCmsCoreBundle:CRUD'
//        ]);
//        $definition->addTag('sonata.admin', [
//            'manager_type' => 'orm',
//            'group' => 'label_group_admin',
//            'label' => 'dashboard.label_option',
//            'label_translator_strategy' => 'sonata.admin.label.strategy.underscore'
//
//        ]);
//        $definition->addMethodCall('setTranslationDomain', ['option']);
//
//        $container->setDefinition('option', $definition);

    }
}
