<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToCustomerClientInterface;
use SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToPersistentCartShareClientInterface;

class PersistentCartShareWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): PersistentCartShareWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(PersistentCartShareWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToPersistentCartShareClientInterface
     */
    public function getPersistentCartShareClient(): PersistentCartShareWidgetToPersistentCartShareClientInterface
    {
        return $this->getProvidedDependency(PersistentCartShareWidgetDependencyProvider::CLIENT_PERSISTENT_CART_SHARE);
    }
}
