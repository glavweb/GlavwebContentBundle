<?php

/*
 * This file is part of the "GlavwebContentBundle" package.
 *
 * (c) GLAVWEB <info@glavweb.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glavweb\ContentBundle\Event\ContentOption;

use Glavweb\ContentBundle\Entity\ContentOption;
use Glavweb\ContentBundle\Entity\Option;
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
     * @var Option
     */
    private $option;

    /**
     * AbstractEvent constructor.
     *
     * @param Option $option
     */
    public function __construct(Option $option)
    {
        $this->option = $option;
    }

    /**
     * @return Option
     */
    public function getOption(): Option
    {
        return $this->option;
    }
}