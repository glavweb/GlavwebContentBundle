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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Glavweb\RestBundle\Mapping\Annotation as RestExtra;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ContentBlock
 *
 * @package Glavweb\ContentBundle\Entity
 * @author Andrey Nilov <nilov@glavweb.ru>
 *
 * @ORM\Table(name="content_blocks")
 * @ORM\Entity
 *
 * @UniqueEntity(
 *     fields={"category", "name"}
 * )
 *
 * @RestExtra\Rest(
 *     methods={"list", "view", "create", "update", "delete"}
 * )
 */
class ContentBlock
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", options={"comment": "ID контент блока"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, options={"comment": "Name"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="wysiwyg", type="boolean",options={"comment": "Is wysiwyg type?"})
     */
    private $wysiwyg = false;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", options={"comment": "Body"})
     */
    private $body;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Glavweb\ContentBundle\Entity\ContentBlockAttribute", mappedBy="contentBlock", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $attributes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }

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
     * Set category
     *
     * @param string $category
     *
     * @return ContentBlock
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
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
     * Get wysiwyg
     *
     * @return boolean
     */
    public function getWysiwyg()
    {
        return $this->wysiwyg;
    }

    /**
     * Set wysiwyg
     *
     * @param boolean $wysiwyg
     *
     * @return ContentBlock
     */
    public function setWysiwyg($wysiwyg)
    {
        $this->wysiwyg = $wysiwyg;

        return $this;
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
     * Add attribute
     *
     * @param ContentBlockAttribute $attribute
     *
     * @return ContentBlock
     */
    public function addAttribute(ContentBlockAttribute $attribute)
    {
        $attribute->setContentBlock($this);
        $this->attributes[] = $attribute;

        return $this;
    }

    /**
     * Remove attribute
     *
     * @param ContentBlockAttribute $attribute
     */
    public function removeAttribute(ContentBlockAttribute $attribute)
    {
        $this->attributes->removeElement($attribute);
    }

    /**
     * Get attributes
     *
     * @return ArrayCollection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
