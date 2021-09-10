<?php

namespace Inamika\BackEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * PreformattedItem
 *
 * @ORM\Table(name="preformatted_item")
 * @ORM\Entity(repositoryClass="Inamika\BackEndBundle\Repository\PreformattedItemRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PreformattedItem
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @Expose
     */
    private $id;

    /**
     * Many features have one preformatted. This is the owning side.
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Preformatted")
     * @ORM\JoinColumn(name="preformatted_id", referencedColumnName="id")
     */
    private $preformatted;

    /**
     * One Cart has One Product.
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id",nullable=true)
     */
    private $product;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="integer")
     * @Expose
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=64, nullable=true)
     * @Assert\NotBlank()
     * @Expose
     */
    private $title;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Preformatted
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Set product.
     *
     * @param string $product
     *
     * @return PreformattedItem
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return string
     */
    public function getProduct()
    {
        return $this->product;
    }
    
    /**
     * Set preformatted.
     *
     * @param string $preformatted
     *
     * @return PreformattedItem
     */
    public function setPreformatted($preformatted)
    {
        $this->preformatted = $preformatted;

        return $this;
    }

    /**
     * Get preformatted.
     *
     * @return string
     */
    public function getPreformatted()
    {
        return $this->preformatted;
    }

    /**
     * Set position.
     *
     * @param float|null $position
     *
     * @return Brand
     */
    public function setPosition($position = null)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position.
     *
     * @return float|null
     */
    public function getPosition()
    {
        return $this->position;
    }

}
