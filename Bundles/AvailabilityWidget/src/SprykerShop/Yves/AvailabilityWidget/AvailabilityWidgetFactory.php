<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityWidget;

use Spryker\Client\AvailabilityStorage\AvailabilityStorageClient;
use Spryker\Yves\Kernel\AbstractFactory;

class AvailabilityWidgetFactory extends AbstractFactory
{

    /**
     * @return AvailabilityStorageClient
     */
    public function getAvailabilityStorageClient()
    {
        return $this->getProvidedDependency(AvailabilityWidgetDependencyProvider::CLIENT_AVAILABILITY_STORAGE);
    }

}
