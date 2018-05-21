<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\BusinessOnBehalfWidget;

use Spryker\Yves\Kernel\AbstractFactory;

class BusinessOnBehalfWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Client\BusinessOnBehalfWidget\Dependency\Client\BusinessOnBehalfWidgetToCustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(BusinessOnBehalfWidgetDependencyProvider::CLIENT_CUSTOMER);
    }
}
