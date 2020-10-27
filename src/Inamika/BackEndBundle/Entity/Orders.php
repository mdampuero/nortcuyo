<?php

namespace Inamika\BackEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="Inamika\BackEndBundle\Repository\OrdersRepository")
 * @ORM\HasLifecycleCallbacks()
 */

class Orders
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     * @Assert\NotBlank()
     */
    private $total;
    
    /**
     * @var float
     *
     * @ORM\Column(name="total_vat", type="float")
     * @Assert\NotBlank()
     */
    private $totalVat;

    /**
     * One Cart has One Customer.
     * @ORM\ManyToOne(targetEntity="Customer", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $customerId;

    /**
     * Many features have one OrersStatus. This is the owning side.
     * @ORM\ManyToOne(targetEntity="OrdersStatus")
     * @ORM\JoinColumn(name="orders_status_id", referencedColumnName="id")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="channel", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $channel;
    
    /**
     * @var string
     *
     * @ORM\Column(name="customer_name", type="string", length=255,nullable=true)
     */
    private $customerName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="transport", type="string", length=255,nullable=true)
     */
    private $transport;
    
    /**
     * @var string
     *
     * @ORM\Column(name="observations", type="string", length=255, nullable=true)
     */
    private $observations;

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
     * @ORM\OneToMany(targetEntity="OrdersItem", mappedBy="orderId")
     * @Assert\NotBlank()
     */
    private $items;
    
    /**
     * @ORM\OneToMany(targetEntity="OrdersTotal", mappedBy="order")
     * @Assert\NotBlank()
     */
    private $totals;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->totals = new ArrayCollection();
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
     * Set status.
     *
     * @param string|null $status
     *
     * @return Orders
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set customerId.
     *
     * @param string $customerId
     *
     * @return OrdersItem
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * Get customerId.
     *
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Set total.
     *
     * @param float $total
     *
     * @return OrdersItem
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total.
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }
   
    /**
     * Set totalVat.
     *
     * @param float $totalVat
     *
     * @return OrdersItem
     */
    public function setTotalVat($totalVat)
    {
        $this->totalVat = $totalVat;

        return $this;
    }

    /**
     * Get totalVat.
     *
     * @return float
     */
    public function getTotalVat()
    {
        return $this->totalVat;
    }

    /**
     * Set observations.
     *
     * @param string $observations
     *
     * @return Orders
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;

        return $this;
    }

    /**
     * Get observations.
     *
     * @return string
     */
    public function getObservations()
    {
        return $this->observations;
    }
    
    /**
     * Set channel.
     *
     * @param string $channel
     *
     * @return Orders
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel.
     *
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }
    /**
     * Set customerName.
     *
     * @param string $customerName
     *
     * @return Orders
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;

        return $this;
    }

    /**
     * Get customerName.
     *
     * @return string
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * Set transport.
     *
     * @param string $transport
     *
     * @return Orders
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Get transport.
     *
     * @return string
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Orders
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
     * @return Orders
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
     * Set isDelete.
     *
     * @param bool $isDelete
     *
     * @return Orders
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

    /**
     * Get items.
     *
     * @return int
     */
    public function getItems()
    {
        return $this->items;
    }
    
    /**
     * Set items.
     *
     * @return Orders
     */
    public function setItems($items)
    {
        return $this->items=$items;
    }
    
    /**
     * Get totals.
     *
     * @return int
     */
    public function getTotals()
    {
        return $this->totals;
    }
    
    /**
     * Set totals.
     *
     * @return Orders
     */
    public function setTotals($totals)
    {
        return $this->totals=$totals;
    }

}
