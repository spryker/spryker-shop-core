<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\BusinessOnBehalfWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\BusinessOnBehalfWidget\Dependency\Client\BusinessOnBehalfWidgetToBusinessOnBehalfClientInterface;
use SprykerShop\Yves\BusinessOnBehalfWidget\Dependency\Client\BusinessOnBehalfWidgetToCustomerClientInterface;

class BusinessOnBehalfWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\BusinessOnBehalfWidget\Dependency\Client\BusinessOnBehalfWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): BusinessOnBehalfWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(BusinessOnBehalfWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\BusinessOnBehalfWidget\Dependency\Client\BusinessOnBehalfWidgetToBusinessOnBehalfClientInterface
     */
    public function getBusinessOnBehalfClient(): BusinessOnBehalfWidgetToBusinessOnBehalfClientInterface
    {
        return $this->getProvidedDependency(BusinessOnBehalfWidgetDependencyProvider::CLIENT_BUSINESS_ON_BEHALF);
    }
}
