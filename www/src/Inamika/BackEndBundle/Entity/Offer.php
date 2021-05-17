<?php

namespace Inamika\BackEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Offer
 *
 * @ORM\Table(name="offer")
 * @ORM\Entity(repositoryClass="Inamika\BackEndBundle\Repository\OfferRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Offer
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
     * Many features have one ProductCategory. This is the owning side.
     * @ORM\ManyToOne(targetEntity="ProductCategory")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true)
     * @Expose
     */
    private $category;

    /**
     * Many features have one Product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=true)
     * @Expose
     */
    private $product;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=32)
     * @Assert\NotBlank()
     * @Expose
     */
    private $title;
    
    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=32, nullable=true)
     * @Expose
     */
    private $color="#f5f6f9";
    
    /**
     * @var string
     *
     * @ORM\Column(name="ribbon", type="string", length=32, nullable=true)
     * @Expose
     */
    private $ribbon;
    
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=32)
     * @Assert\NotBlank()
     * @Expose
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=64, nullable=true)
     * @Assert\File(
     *     maxSize = "2048k",
     *     mimeTypes = {"image/png","image/jpeg","image/pjpeg","image/gif"},
     *     mimeTypesMessage = "Seleccione un formato de imagen válido"
     * )
     * @Expose
     */
    private $picture;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
    
    /**
     * @var \Date
     * @Assert\NotBlank()
     * @ORM\Column(name="date_from", type="date")
     */
    private $dateFrom;
    
    /**
     * @var \Date
     * @Assert\NotBlank()
     * @ORM\Column(name="date_to", type="date")
     */
    private $dateTo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_delete", type="boolean")
     */
    private $isDelete=false;
    
    /**
     * @var bool
     *
     */
    private $isActive=false;

    /**
     * @var float
     *
     * @Expose
     */
    private $pictureXs;
    /**
     * @var float
     *
     * @Expose
     */
    private $pictureSm;
    /**
     * @var float
     *
     * @Expose
     */
    private $pictureMD;
    /**
     * @var float
     *
     * @Expose
     */
    private $pictureLg;
    /**
     * @var float
     *
     * @Expose
     */
    private $pictureOr;

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
     * Set title.
     *
     * @param string $title
     *
     * @return Offer
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Set color.
     *
     * @param string $color
     *
     * @return Offer
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color.
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }
    
    /**
     * Set ribbon.
     *
     * @param string $ribbon
     *
     * @return Offer
     */
    public function setRibbon($ribbon)
    {
        $this->ribbon = $ribbon;

        return $this;
    }

    /**
     * Get ribbon.
     *
     * @return string
     */
    public function getRibbon()
    {
        return $this->ribbon;
    }

    /**
     * Set category.
     *
     * @param string|null $category
     *
     * @return Product
     */
    public function setCategory($category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return string|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set product.
     *
     * @param string|null $product
     *
     * @return Product
     */
    public function setProduct($product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return string|null
     */
    public function getProduct()
    {
        return $this->product;
    }
    
    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Offer
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

     /**
     * Set picture
     *
     * @param string $picture
     *
     * @return User
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set pictureXs.
     *
     * @param float|null $pictureXs
     *
     * @return Product
     */
    public function setPictureXs($pictureXs = null)
    {
        $this->pictureXs = $pictureXs;

        return $this;
    }

    /**
     * Get pictureXs.
     *
     * @return float|null
     */
    public function getPictureXs()
    {
        return $this->pictureXs;
    }
    
    /**
     * Set pictureSm.
     *
     * @param float|null $pictureSm
     *
     * @return Product
     */
    public function setPictureSm($pictureSm = null)
    {
        $this->pictureSm = $pictureSm;

        return $this;
    }

    /**
     * Get pictureSm.
     *
     * @return float|null
     */
    public function getPictureSm()
    {
        return $this->pictureSm;
    }
    
    /**
     * Set pictureMD.
     *
     * @param float|null $pictureMD
     *
     * @return Product
     */
    public function setPictureMd($pictureMD = null)
    {
        $this->pictureMD = $pictureMD;

        return $this;
    }

    /**
     * Get pictureMD.
     *
     * @return float|null
     */
    public function getPictureMd()
    {
        return $this->pictureMD;
    }
    
    /**
     * Set pictureLg.
     *
     * @param float|null $pictureLg
     *
     * @return Product
     */
    public function setPictureLg($pictureLg = null)
    {
        $this->pictureLg = $pictureLg;

        return $this;
    }

    /**
     * Get pictureLg.
     *
     * @return float|null
     */
    public function getPictureLg()
    {
        return $this->pictureLg;
    }
    
    /**
     * Set pictureOr.
     *
     * @param float|null $pictureOr
     *
     * @return Product
     */
    public function setPictureOr($pictureOr = null)
    {
        $this->pictureOr = $pictureOr;

        return $this;
    }

    /**
     * Get pictureOr.
     *
     * @return float|null
     */
    public function getPictureOr()
    {
        return $this->pictureOr;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Offer
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * Set dateFrom.
     *
     * @param \Date $dateFrom
     *
     * @return Offer
     */
    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    /**
     * Get dateFrom.
     *
     * @return \Date
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }
    
    /**
     * Set dateTo.
     *
     * @param \Date $dateTo
     *
     * @return Offer
     */
    public function setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;
        return $this;
    }

    /**
     * Get dateTo.
     *
     * @return \Date
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return Offer
     */
    public function setUpdateAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set isDelete.
     *
     * @param bool $isDelete
     *
     * @return Offer
     */
    public function setIsDelete($isDelete)
    {
        $this->isDelete = $isDelete;

        return $this;
    }

    /**
     * Get isDelete.
     *
     * @return bool
     */
    public function getIsDelete()
    {
        return $this->isDelete;
    }
    
    /**
     * Set isActive.
     *
     * @param bool $isActive
     *
     * @return Offer
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

     /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt=new \DateTime();
    }
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt=new \DateTime();
    }
}
