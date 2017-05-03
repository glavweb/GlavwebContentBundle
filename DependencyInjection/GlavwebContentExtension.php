<?php

/*
 * This file is part of the "GlavwebContentBlockAdmin" package.
 *
 * (c) GLAVWEB <info@glavweb.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glavweb\ContentBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class GlavwebContentExtension
 *
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 *
 * @package Glavweb\ContentBundle\DependencyInjection
 * @author Andrey Nilov <nilov@glavweb.ru>
 */
class GlavwebContentExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('admin.yml');
        $loader->load('services.yml');

//        $this->addAdminObjectDefinitions($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function addAdminObjectDefinitions(ContainerBuilder $container)
    {
        $em = $container->get('doctrine')->getManager();

        $definition = new Definition('Glavweb\ContentBundle\Admin\OptionAdmin', [
            null,
            'Glavweb\ContentBundle\Entity\Option',
            'GlavwebCmsCoreBundle:CRUD'
        ]);
        $definition->addTag('sonata.admin', [
            'manager_type' => 'orm',
            'group' => 'label_group_admin',
            'label' => 'dashboard.label_option',
            'label_translator_strategy' => 'sonata.admin.label.strategy.underscore'

        ]);
        $definition->addMethodCall('setTranslationDomain', ['option']);

        $container->setDefinition('option', $definition);
    }
}
