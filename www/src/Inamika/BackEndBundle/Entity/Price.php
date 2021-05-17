<?php

namespace Inamika\BackEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Price
 *
 * @ORM\Table(name="price")
 * @ORM\Entity(repositoryClass="Inamika\BackEndBundle\Repository\PriceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Price
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
     * @ORM\Column(name="file", type="string", length=64, nullable=true)
     * @Assert\NotBlank()
     * @Expose
     */
    private $file;

    /**
     * @var integer
     *
     * @ORM\Column(name="count_updated", type="integer")
     */
    private $countUpdated;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="count_created", type="integer")
     */
    private $countCreated;

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
     * Set picture
     *
     * @param string $picture
     *
     * @return Price
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
     * Set file
     *
     * @param string $file
     *
     * @return Price
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * Set countUpdated
     *
     * @param string $countUpdated
     *
     * @return Price
     */
    public function setCountUpdated($countUpdated)
    {
        $this->countUpdated = $countUpdated;

        return $this;
    }

    /**
     * Get countUpdated
     *
     * @return string
     */
    public function getCountUpdated()
    {
        return $this->countUpdated;
    }
    
    /**
     * Set countCreated
     *
     * @param string $countCreated
     *
     * @return Price
     */
    public function setCountCreated($countCreated)
    {
        $this->countCreated = $countCreated;

        return $this;
    }

    /**
     * Get countCreated
     *
     * @return string
     */
    public function getCountCreated()
    {
        return $this->countCreated;
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
     * @return Price
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
     * @return Price
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
     * @return Price
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
