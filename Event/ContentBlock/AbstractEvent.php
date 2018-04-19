<?php

/*
 * This file is part of the "GlavwebContentBundle" package.
 *
 * (c) GLAVWEB <info@glavweb.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glavweb\ContentBundle\Event\ContentBlock;

use Glavweb\ContentBundle\Entity\ContentBlock;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class AbstractEvent
 *
 * @package Glavweb\ContentBundle
 * @author Andrey Nilov <nilov@glavweb.ru>
 */
abstract class AbstractEvent extends Event
{
    /**
     * @var ContentBlock
     */
    private $contentBlock;

    /**
     * AbstractEvent constructor.
     *
     * @param ContentBlock $contentBlock
     */
    public function __construct(ContentBlock $contentBlock)
    {
        $this->contentBlock = $contentBlock;
    }

    /**
     * @return ContentBlock
     */
    public function getContentBlock(): ContentBlock
    {
        return $this->contentBlock;
    }
}