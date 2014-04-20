<?php

namespace SL\FrontBundle\Block\Settings;

class ItemsBlockSettingsModel
{
    /**
     * @var int
     */
    private $limit = 16;

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }
}
