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
 * Class ContentOptionEvents
 *
 * @package Glavweb\CompositeObjectBundle
 * @author Andrey Nilov <nilov@glavweb.ru>
 */
final class ContentOptionEvents
{
    const PRE_PERSIST  = 'content_option.pre_persist';
    const POST_PERSIST = 'content_option.post_persist';
    const PRE_UPDATE   = 'content_option.pre_update';
    const POST_UPDATE  = 'content_option.post_update';
    const PRE_REMOVE   = 'content_option.pre_remove';
    const POST_REMOVE  = 'content_option.post_remove';
}
