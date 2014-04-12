<?php

use NS\CoreBundle\CoreKernel;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * App Kernel
 *
 */
class AppKernel extends CoreKernel
{
	/**
	 * @return Bundle[]
	 */
	protected function getUserBundles()
	{
		return array(
            new \SL\FrontBundle\SLFrontBundle(),
		);
	}
}
