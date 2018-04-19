<?php

/*
 * This file is part of the "GlavwebContentBundle" package.
 *
 * (c) GLAVWEB <info@glavweb.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glavweb\ContentBundle;

/**
 * Class ContentBlockEvents
 *
 * @package Glavweb\CompositeObjectBundle
 * @author Andrey Nilov <nilov@glavweb.ru>
 */
final class ContentBlockEvents
{
    const PRE_PERSIST  = 'content_block.pre_persist';
    const POST_PERSIST = 'content_block.post_persist';
    const PRE_UPDATE   = 'content_block.pre_update';
    const POST_UPDATE  = 'content_block.post_update';
    const PRE_REMOVE   = 'content_block.pre_remove';
    const POST_REMOVE  = 'content_block.post_remove';
}
