<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin;

use Pyz\Yves\Twig\Dependency\Plugin\TwigFunctionPluginInterface;
use Silex\Application;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig_SimpleFunction;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
// TODO: replace this with service provider
class TwigCustomer extends AbstractPlugin implements TwigFunctionPluginInterface
{
    /**
     * @param \Silex\Application $application
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions(Application $application)
    {
        return [
            new Twig_SimpleFunction('getUsername', function () {
                if (!$this->getFactory()->getCustomerClient()->isLoggedIn()) {
                    return null;
                }

                return $this->getFactory()->getCustomerClient()->getCustomer()->getEmail();
            }),
            new Twig_SimpleFunction('isLoggedIn', function () {
                return $this->getFactory()->getCustomerClient()->isLoggedIn();
            }),
        ];
    }
}
