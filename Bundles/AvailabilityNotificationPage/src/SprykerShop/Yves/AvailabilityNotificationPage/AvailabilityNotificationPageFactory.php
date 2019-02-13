<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\AvailabilityNotificationPage\Dependency\Client\AvailabilityNotificationPageToAvailabilityNotificationClientInterface;
use SprykerShop\Yves\AvailabilityNotificationPage\Dependency\Client\AvailabilityNotificationPageToCustomerClientInterface;

class AvailabilityNotificationPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\AvailabilityNotificationPage\Dependency\Client\AvailabilityNotificationPageToAvailabilityNotificationClientInterface
     */
    public function getAvailabilityNotificationClient(): AvailabilityNotificationPageToAvailabilityNotificationClientInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationPageDependencyProvider::CLIENT_AVAILABILITY_NOTIFICATION);
    }

    /**
     * @return \SprykerShop\Yves\AvailabilityNotificationPage\Dependency\Client\AvailabilityNotificationPageToCustomerClientInterface
     */
    public function getCustomerClient(): AvailabilityNotificationPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationPageDependencyProvider::CLIENT_CUSTOMER);
    }
}
