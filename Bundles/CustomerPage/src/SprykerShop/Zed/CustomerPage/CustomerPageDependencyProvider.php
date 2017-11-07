<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Zed\CustomerPage;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CustomerPageDependencyProvider extends AbstractBundleDependencyProvider
{
    const SALES_FACADE = 'SALES_FACADE';
    const NEWSLETTER_FACADE = 'NEWSLETTER_FACADE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addSalesFacade($container);
        $container = $this->addNewsletterFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container[self::SALES_FACADE] = function (Container $container) {
            return $container->getLocator()->sales()->facade();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addNewsletterFacade(Container $container): Container
    {
        $container[self::NEWSLETTER_FACADE] = function (Container $container) {
            return $container->getLocator()->newsletter()->facade();
        };

        return $container;
    }
}
