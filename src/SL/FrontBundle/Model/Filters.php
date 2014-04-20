<?php

namespace SL\FrontBundle\Model;

use NS\CatalogBundle\Entity\Item;

class Filters
{
    /**
     * @var Item
     */
    private $vendor;

    /**
     * @var float
     */
    private $priceFrom;

    /**
     * @var float
     */
    private $priceTo;

    /**
     * @param float $priceFrom
     */
    public function setPriceFrom($priceFrom)
    {
        $this->priceFrom = $priceFrom;
    }

    /**
     * @return float
     */
    public function getPriceFrom()
    {
        return $this->priceFrom;
    }

    /**
     * @param float $priceTo
     */
    public function setPriceTo($priceTo)
    {
        $this->priceTo = $priceTo;
    }

    /**
     * @return float
     */
    public function getPriceTo()
    {
        return $this->priceTo;
    }

    /**
     * @param \NS\CatalogBundle\Entity\Item $vendor
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
    }

    /**
     * @return \NS\CatalogBundle\Entity\Item
     */
    public function getVendor()
    {
        return $this->vendor;
    }
}