<?php

/*
 * This file is part of the "GlavwebContentBlockAdmin" package.
 *
 * (c) GLAVWEB <info@glavweb.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glavweb\ContentBundle\Block;

use Glavweb\ContentBundle\Entity\Option;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractAdminBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class OptionsBlockService
 *
 * @package Glavweb\ContentBundle\Block
 * @author Andrey Nilov <nilov@glavweb.ru>
 */
class OptionsBlockService extends AbstractAdminBlockService
{
    /**
     * @param string          $name
     * @param EngineInterface $templating
     * @param Registry        $doctrine
     */
    public function __construct($name, EngineInterface $templating, Registry $doctrine)
    {
        parent::__construct($name, $templating);

        $this->doctrine = $doctrine;
    }

    /**
     * @param BlockContextInterface $blockContext
     * @param Response $response
     * @return Response
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $em = $this->doctrine->getManager();
        $options    = $em->getRepository(Option::class)->findAll();

        $categories = [];
        foreach ($options as $option) {
            $category = $option->getCategory();
            if (!in_array($category, $categories)) {
                $categories[] = $category;
            }
        }

        return $this->renderResponse($blockContext->getTemplate(), array(
            'options'    => $options,
            'categories' => $categories,
            'block'      => $blockContext->getBlock(),
            'settings'   => $blockContext->getSettings()
        ), $response);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'template'   => 'GlavwebContentBundle:blocks:options_block.html.twig',
            'isExternal' => true
        ));
    }

    /**
     * @param ErrorElement $errorElement
     * @param BlockInterface $block
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        $errorElement
            ->with('settings.user')
            ->assertNotNull(array())
            ->assertNotBlank()
            ->end();
    }
}