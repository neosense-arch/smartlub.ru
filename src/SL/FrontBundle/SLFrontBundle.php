<?php

namespace SL\FrontBundle;

use NS\CoreBundle\Bundle\CoreBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SLFrontBundle extends Bundle implements CoreBundle
{
    /**
     * Retrieves human-readable bundle title
     *
     * @return string
     */
    public function getTitle()
    {
        return 'smartlub.ru';
    }
}
