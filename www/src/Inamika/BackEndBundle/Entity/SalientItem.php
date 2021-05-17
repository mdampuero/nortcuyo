<?php

namespace Inamika\BackEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * SalientItem
 *
 * @ORM\Table(name="salient_item")
 * @ORM\Entity(repositoryClass="Inamika\BackEndBundle\Repository\SalientItemRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class SalientItem
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
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Salient", inversedBy="items",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="salient_id", referencedColumnName="id")
     */
    private $salient;

    /**
     * One Cart has One Product.
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

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
     * Set salient.
     *
     * @param string $salient
     *
     * @return SalientItem
     */
    public function setSalient($salient)
    {
        $this->salient = $salient;

        return $this;
    }

    /**
     * Get salient.
     *
     * @return string
     */
    public function getSalient()
    {
        return $this->salient;
    }

    /**
     * Set product.
     *
     * @param string $product
     *
     * @return SalientItem
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

}
