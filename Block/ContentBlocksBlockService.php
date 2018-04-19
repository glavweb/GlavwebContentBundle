<?php

/*
 * This file is part of the "GlavwebContentBundle" package.
 *
 * (c) GLAVWEB <info@glavweb.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glavweb\ContentBundle\Block;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Glavweb\ContentBundle\Entity\ContentBlock;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractAdminBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ContentBlocksBlockService
 *
 * @package Glavweb\ContentBundle\Block
 * @author Andrey Nilov <nilov@glavweb.ru>
 */
class ContentBlocksBlockService extends AbstractAdminBlockService
{
    /**
     * @var Registry
     */
    private $doctrine;

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
        $contentBlocks = $em->getRepository(ContentBlock::class)->findBy([], ['id' => 'ASC'], 10);

        return $this->renderResponse($blockContext->getTemplate(), array(
            'contentBlocks' => $contentBlocks,
            'block'         => $blockContext->getBlock(),
            'settings'      => $blockContext->getSettings()
        ), $response);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'template'   => 'GlavwebContentBundle:blocks:content_blocks_block.html.twig',
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