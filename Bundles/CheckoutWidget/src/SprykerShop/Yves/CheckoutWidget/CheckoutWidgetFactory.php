<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CheckoutWidget\Dependency\Client\CheckoutWidgetToCheckoutClientInterface;

class CheckoutWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CheckoutPage\Plugin\CheckoutBreadcrumbPlugin
     */
    public function getCheckoutBreadcrumbPlugin()
    {
        return $this->getProvidedDependency(CheckoutWidgetDependencyProvider::PLUGIN_CHECKOUT_BREADCRUMB);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutWidget\Dependency\Client\CheckoutWidgetToCheckoutClientInterface
     */
    public function getCheckoutClient(): CheckoutWidgetToCheckoutClientInterface
    {
        return $this->getProvidedDependency(CheckoutWidgetDependencyProvider::CLIENT_CHECKOUT);
    }
}
