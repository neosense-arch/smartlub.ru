<?php

namespace SL\FrontBundle\Block\Settings;

class FiltersBlockSettingsModel
{
    /**
     * @var int
     */
    private $vendorCategoryId;

    /**
     * @var float
     */
    private $priceFrom = 500;

    /**
     * @var float
     */
    private $priceTo = 5000;

    /**
     * @var int
     */
    private $priceStep = 500;

    /**
     * @param int $vendorCategoryId
     */
    public function setVendorCategoryId($vendorCategoryId)
    {
        $this->vendorCategoryId = $vendorCategoryId;
    }

    /**
     * @return int
     */
    public function getVendorCategoryId()
    {
        return $this->vendorCategoryId;
    }

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
     * @param int $priceStep
     */
    public function setPriceStep($priceStep)
    {
        $this->priceStep = $priceStep;
    }

    /**
     * @return int
     */
    public function getPriceStep()
    {
        return $this->priceStep;
    }
}
