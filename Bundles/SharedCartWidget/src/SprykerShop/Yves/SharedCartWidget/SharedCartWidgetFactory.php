<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget;

use Spryker\Yves\Kernel\AbstractFactory;

class SharedCartWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\SharedCartWidget\Dependency\Client\SharedCartWidgetToCustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(SharedCartWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\SharedCartWidget\Dependency\Client\SharedCartWidgetToMultiCartClientInterface
     */
    public function getMultiCartClient()
    {
        return $this->getProvidedDependency(SharedCartWidgetDependencyProvider::CLIENT_MULTI_CART);
    }
}
