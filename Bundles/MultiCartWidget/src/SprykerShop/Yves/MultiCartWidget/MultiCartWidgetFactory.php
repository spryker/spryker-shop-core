<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget;

use Spryker\Yves\Kernel\AbstractFactory;

class MultiCartWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\MultiCartWidget\Dependency\Client\MultiCartWidgetToMultiCartClientInterface
     */
    public function getMultiCartClient()
    {
        return $this->getProvidedDependency(MultiCartWidgetDependencyProvider::CLIENT_MULTI_CART);
    }
}
