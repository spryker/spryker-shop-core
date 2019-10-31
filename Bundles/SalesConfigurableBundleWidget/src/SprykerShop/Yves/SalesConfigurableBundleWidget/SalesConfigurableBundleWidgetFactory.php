<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesConfigurableBundleWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\SalesConfigurableBundleWidget\Grouper\SalesOrderConfiguredBundleGrouper;
use SprykerShop\Yves\SalesConfigurableBundleWidget\Grouper\SalesOrderConfiguredBundleGrouperInterface;
use SprykerShop\Yves\SalesConfigurableBundleWidget\Model\ConfiguredBundleChecker;

/**
 * @method \SprykerShop\Yves\SalesConfigurableBundleWidget\SalesConfigurableBundleWidgetConfig getConfig()
 */
class SalesConfigurableBundleWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\SalesConfigurableBundleWidget\Grouper\SalesOrderConfiguredBundleGrouperInterface
     */
    public function createSalesOrderConfiguredBundleGrouper(): SalesOrderConfiguredBundleGrouperInterface
    {
        return new SalesOrderConfiguredBundleGrouper();
    }

    /**
     * @return \SprykerShop\Yves\SalesConfigurableBundleWidget\Model\ConfiguredBundleChecker
     */
    public function createConfiguredBundleChecker(): ConfiguredBundleChecker
    {
        return new ConfiguredBundleChecker($this->getMessenger());
    }

    /**
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    public function getMessenger(): FlashMessengerInterface
    {
        return $this->getProvidedDependency(SalesConfigurableBundleWidgetDependencyProvider::FLASH_MESSENGER);
    }
}
