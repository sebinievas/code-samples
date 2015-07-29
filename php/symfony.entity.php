<?php

/* -------------------------------------------------
** An entity created for Symfony ecommerce system
** ---------------------------------------------- */


namespace Sample\StorefrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product extends Entity
{
    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="instance_of")
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="children")
     * @ORM\JoinColumn(name="instance_of", referencedColumnName="id")
     */
    protected $instance_of = null;

    /**
     * @ORM\Column(type="string", length=200, unique=TRUE)
     * @Assert\NotBlank()
     */
    protected $guid;

    /**
     * @ORM\Column(type="text", nullable=TRUE)
     */
    protected $description;

    /**
     * @ORM\Column(type="decimal", scale=4, nullable=TRUE)
     */
    protected $cost;

    /**
     * @ORM\Column(type="decimal", scale=4, nullable=TRUE)
     */
    protected $sale_price;

    /**
     * @ORM\Column(type="decimal", scale=4, nullable=TRUE)
     */
    protected $retail_price;

    /**
     * @ORM\Column(type="decimal", scale=4, nullable=TRUE)
     */
    protected $weight;

    /**
     * @ORM\Column(type="string", length=40, nullable=TRUE)
     */
    protected $weight_unit;

    /**
     * @ORM\Column(type="integer", nullable=TRUE)
     */
    protected $tax_class_id;

    /**
     * @ORM\Column(type="boolean", nullable=FALSE)
     */
    protected $is_active = true;

    /**
     * @ORM\Column(type="boolean", nullable=FALSE)
     */
    protected $is_taxable = false;

    /**
     * @ORM\ManyToOne(targetEntity="CollectionProduct", inversedBy="collection_id")
     */
    protected $collections;

    /**
     * @ORM\Column(type="datetime", nullable=FALSE)
     * @Gedmo\Timestampable(on="create")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     * @Gedmo\Timestampable(on="update")
     */
    protected $modified_at;

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     */
    protected $deleted_at;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * Set guid
     *
     * @param string $guid
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
    }

    /**
     * Get guid
     *
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set cost
     *
     * @param decimal $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * Get cost
     *
     * @return decimal
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set sale_price
     *
     * @param decimal $salePrice
     */
    public function setSalePrice($salePrice)
    {
        $this->sale_price = $salePrice;
    }

    /**
     * Get sale_price
     *
     * @return decimal
     */
    public function getSalePrice()
    {
        return $this->getLocalValue('sale_price');
    }

    /**
     * Set retail_price
     *
     * @param decimal $retailPrice
     */
    public function setRetailPrice($retailPrice)
    {
        $this->retail_price = $retailPrice;
    }

    /**
     * Get retail_price
     *
     * @return decimal
     */
    public function getRetailPrice()
    {
        return (!is_null($this->retail_price) || is_null($this->getInstanceOf()))
            ? $this->retail_price
            : $this->getInstanceOf()->getRetailPrice();
    }

    /**
     * Set weight
     *
     * @param decimal $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * Get weight
     *
     * @return decimal
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set weight_unit
     *
     * @param string $weightUnit
     */
    public function setWeightUnit($weightUnit)
    {
        $this->weight_unit = $weightUnit;
    }

    /**
     * Get weight_unit
     *
     * @return string
     */
    public function getWeightUnit()
    {
        return $this->weight_unit;
    }

    /**
     * Set tax_class_id
     *
     * @param integer $taxClassId
     */
    public function setTaxClassId($taxClassId)
    {
        $this->tax_class_id = $taxClassId;
    }

    /**
     * Get tax_class_id
     *
     * @return integer
     */
    public function getTaxClassId()
    {
        return $this->tax_class_id;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;
    }

    /**
     * Get is_active
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set is_taxable
     *
     * @param boolean $isTaxable
     */
    public function setIsTaxable($isTaxable)
    {
        $this->is_taxable = $isTaxable;
    }

    /**
     * Get is_taxable
     *
     * @return boolean
     */
    public function getIsTaxable()
    {
        return $this->is_taxable;
    }

    /**
     * Set is_digital
     *
     * @param boolean $isDigital
     */
    public function setIsDigital($isDigital)
    {
        $this->is_digital = $isDigital;
    }

    /**
     * Get is_digital
     *
     * @return boolean
     */
    public function getIsDigital()
    {
        return $this->is_digital;
    }

    /**
     * Add children
     *
     * @param Sample\StorefrontBundle\Entity\Product $children
     */
    public function addProduct(\Sample\StorefrontBundle\Entity\Product $children)
    {
        $this->children[] = $children;
    }

    /**
     * Get children
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set instance_of
     *
     * @param Sample\StorefrontBundle\Entity\Product $instanceOf
     */
    public function setInstanceOf(\Sample\StorefrontBundle\Entity\Product $instanceOf)
    {
        $this->instance_of = $instanceOf;
    }

    /**
     * Get instance_of
     *
     * @return Sample\StorefrontBundle\Entity\Product
     */
    public function getInstanceOf()
    {
        return $this->instance_of;
    }

    public function getLocalValue($property_name)
    {
        static $method_calls = array();

        if (!array_key_exists($property_name, $method_calls))
        {
            $method_calls[$property_name] = 'get'.ucwords(str_replace('_', '', $property_name));
        }

        if (!is_null($this->$property_name) || is_null($this->getInstanceOf()))
        {
            return $this->$property_name;
        }

        $method = $method_calls[$property_name];
        return $this->getInstanceOf()->$method();
    }

    /**
     * Set collections
     *
     * @param Sample\StorefrontBundle\Entity\CollectionProduct $collections
     */
    public function setCollections(\Sample\StorefrontBundle\Entity\CollectionProduct $collections)
    {
        $this->collections = $collections;
    }

    /**
     * Get collections
     *
     * @return Sample\StorefrontBundle\Entity\CollectionProduct
     */
    public function getCollections()
    {
        return $this->collections;
    }
}