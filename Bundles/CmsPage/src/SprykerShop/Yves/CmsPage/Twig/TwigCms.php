<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CmsPage\Plugin;

use Silex\Application;
use Spryker\Yves\Twig\Plugin\TwigFunctionPluginInterface;
use Twig_SimpleFunction;

class TwigCms implements TwigFunctionPluginInterface
{



    /**
     * @param \Silex\Application $application
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions(Application $application)
    {
        return [
            
        ];
    }



}
