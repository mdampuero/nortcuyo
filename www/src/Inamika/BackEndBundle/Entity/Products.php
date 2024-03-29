<?php

namespace Inamika\BackEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="Inamika\BackEndBundle\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"ean"}, repositoryMethod="getUniqueNotDeleted")
 * @ExclusionPolicy("all")
 */
class Products
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
     * @ORM\Column(name="category", type="string", length=64,nullable=true)
     * @Expose
     */
    private $category;

    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="ProductPicture", mappedBy="product")
     * @Expose
     */
    private $pictures;

    /**
     * @var string
     *
     * @ORM\Column(name="ean", type="string", length=32)
     * @Assert\NotBlank()
     * @Expose
     */
    private $ean;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     * @Expose
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="presentation", type="string", length=255)
     * @Assert\NotBlank()
     * @Expose
     */
    private $presentation;
        
    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=255,nullable=true)
     * @Expose
     */
    private $brand;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     * @Expose
     */
    private $price=0;

    /**
     * @var float
     *
     * @ORM\Column(name="price_min", type="float")
     * @Expose
     */
    private $priceMin=0;

    /**
     * @var float
     *
     * @ORM\Column(name="price_max", type="float")
     * @Expose
     */
    private $priceMax=0;
   
    /**
     * @var integer
     *
     * @ORM\Column(name="unit", type="integer")
     * @Assert\NotBlank()
     * @Expose
     */
    private $unit=1;
    
    /**
     * @var float
     *
     * @ORM\Column(name="vat", type="float")
     * @Assert\NotBlank()
     * @Expose
     */
    private $vat=1.21;
    
    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=64,nullable=true)
     * @Expose
     */
    private $picture;

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
    private $pictureOr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text",nullable=true)
     * @Expose
     */
    private $description;
    
    /**
     * @var string|null
     *
     * @ORM\Column(name="tags", type="text",nullable=true)
     * @Expose
     */
    private $tags;

    /**
     * @var boolean
     *
     * @Expose
     */
    private $isFavorite=false;
    
    /**
     * @var boolean
     * @ORM\Column(name="is_update", type="boolean")
     * @Expose
     */
    private $isUpdate=false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

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

    public function __construct() {
        $this->pictures = new ArrayCollection();
    }

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
     * Set name.
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set currency.
     *
     * @param string $currency
     *
     * @return Product
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency.
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Get pictures.
     *
     * @return array
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * Set ean.
     *
     * @param string|null $ean
     *
     * @return Product
     */
    public function setEan($ean = null)
    {
        $this->ean = $ean;

        return $this;
    }

    /**
     * Get ean.
     *
     * @return string|null
     */
    public function getEan()
    {
        return $this->ean;
    }
   
    
    /**
     * Set presentation.
     *
     * @param string|null $presentation
     *
     * @return Product
     */
    public function setPresentation($presentation = null)
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * Get presentation.
     *
     * @return string|null
     */
    public function getPresentation()
    {
        return $this->presentation;
    }
    
    
    /**
     * Set brand.
     *
     * @param string|null $brand
     *
     * @return Product
     */
    public function setBrand($brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand.
     *
     * @return string|null
     */
    public function getBrand()
    {
        return $this->brand;
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
     * Set picture.
     *
     * @param string|null $picture
     *
     * @return Product
     */
    public function setPicture($picture = null)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture.
     *
     * @return string|null
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Set tags.
     *
     * @param string $tags
     *
     * @return Product
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags.
     *
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set price.
     *
     * @param float|null $price
     *
     * @return Product
     */
    public function setPrice($price = null)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return float|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set priceMin.
     *
     * @param float|null $priceMin
     *
     * @return Product
     */
    public function setPriceMin($priceMin = null)
    {
        $this->priceMin = $priceMin;

        return $this;
    }

    /**
     * Get priceMin.
     *
     * @return float|null
     */
    public function getPriceMin()
    {
        return $this->priceMin;
    }
    
    /**
     * Set priceMax.
     *
     * @param float|null $priceMax
     *
     * @return Product
     */
    public function setPriceMax($priceMax = null)
    {
        $this->priceMax = $priceMax;

        return $this;
    }

    /**
     * Get priceMax.
     *
     * @return float|null
     */
    public function getPriceMax()
    {
        return $this->priceMax;
    }
    
    /**
     * Set unit.
     *
     * @param float|null $unit
     *
     * @return Product
     */
    public function setUnit($unit = null)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit.
     *
     * @return float|null
     */
    public function getUnit()
    {
        return $this->unit;
    }
    
    /**
     * Set vat.
     *
     * @param float|null $vat
     *
     * @return Product
     */
    public function setVat($vat = null)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Get vat.
     *
     * @return float|null
     */
    public function getVat()
    {
        return $this->vat;
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
     * @return Product
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
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return Product
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    

    /**
     * Set isFavorite.
     *
     * @param bool $isFavorite
     *
     * @return Product
     */
    public function setIsFavorite($isFavorite)
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }

    /**
     * Get isFavorite.
     *
     * @return bool
     */
    public function getIsFavorite()
    {
        return $this->isFavorite;
    }
    
    /**
     * Set isDelete.
     *
     * @param bool $isDelete
     *
     * @return Product
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
     * Set isUpdate.
     *
     * @param bool $isUpdate
     *
     * @return Product
     */
    public function setIsUpdate($isUpdate)
    {
        $this->isUpdate = $isUpdate;

        return $this;
    }

    /**
     * Get isUpdate.
     *
     * @return bool
     */
    public function getIsUpdate()
    {
        return $this->isUpdate;
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
