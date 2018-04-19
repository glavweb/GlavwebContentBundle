<?php

/*
 * This file is part of the "GlavwebContentBundle" package.
 *
 * (c) GLAVWEB <info@glavweb.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glavweb\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContentBlockAttribute
 *
 * @package Glavweb\ContentBundle\Entity
 * @author Andrey Nilov <nilov@glavweb.ru>
 *
 * @ORM\Table(name="content_block_attributes")
 * @ORM\Entity
 */
class ContentBlockAttribute
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", options={"comment": "ID аттрибута"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, options={"comment": "Название"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", options={"comment": "Содержание"})
     */
    private $body;

    /**
     * @var ContentBlock
     *
     * @ORM\ManyToOne(targetEntity="Glavweb\ContentBundle\Entity\ContentBlock", inversedBy="attributes")
     * @ORM\JoinColumn(name="content_block_id", referencedColumnName="id", nullable=false)
     */
    private $contentBlock;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName() ?: 'n/a';
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ContentBlock
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return ContentBlock
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set contentBlock
     *
     * @param ContentBlock $contentBlock
     *
     * @return ContentBlockAttribute
     */
    public function setContentBlock(ContentBlock $contentBlock)
    {
        $this->contentBlock = $contentBlock;

        return $this;
    }

    /**
     * Get contentBlock
     *
     * @return ContentBlock
     */
    public function getContentBlock()
    {
        return $this->contentBlock;
    }
}
