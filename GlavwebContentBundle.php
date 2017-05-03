<?php

/*
 * This file is part of the "GlavwebContentBlockAdmin" package.
 *
 * (c) GLAVWEB <info@glavweb.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glavweb\ContentBundle;

use Glavweb\ContentBundle\Admin\OptionAdmin;
use Glavweb\ContentBundle\DependencyInjection\Compiler\DataTransformerPass;
use Glavweb\ContentBundle\Service\FixtureCreator;
use Sonata\AdminBundle\Admin\Pool;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class GlavwebContentBundle
 *
 * @package Glavweb\ContentBundle
 * @author Andrey Nilov <nilov@glavweb.ru>
 */
class GlavwebContentBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
//        parent::build($container);
//
//        $container->addCompilerPass(new DataTransformerPass());
    }

    /**
     * Boots the Bundle.
     */
    public function boot()
    {
//        $optionAdmin = new OptionAdmin('option', 'Glavweb\ContentBundle\Entity\Option', 'GlavwebCmsCoreBundle:CRUD');
//        $this->container->set('option', $optionAdmin);
//
//        /** @var Pool $pool */
//        $pool = $this->container->get('sonata.admin.pool');
//
//        $admins = $pool->getAdminServiceIds();
//        $admins[] = 'option';
//
//        $classes = $pool->getAdminClasses();
//        $classes['Glavweb\ContentBundle\Admin\OptionAdmin'] = ['option'];
//
//        $pool->setAdminServiceIds($admins);
////        $pool->setAdminGroups(array($groups));
//        $pool->setAdminClasses($classes);
    }

}
