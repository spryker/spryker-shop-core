<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesConfigurableBundleWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\SalesConfigurableBundleWidget\Checker\ConfiguredBundleChecker;
use SprykerShop\Yves\SalesConfigurableBundleWidget\Checker\ConfiguredBundleCheckerInterface;
use SprykerShop\Yves\SalesConfigurableBundleWidget\Dependency\Client\SalesConfigurableBundleWidgetToMessengerClientInterface;
use SprykerShop\Yves\SalesConfigurableBundleWidget\Grouper\SalesOrderConfiguredBundleGrouper;
use SprykerShop\Yves\SalesConfigurableBundleWidget\Grouper\SalesOrderConfiguredBundleGrouperInterface;

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
     * @return \SprykerShop\Yves\SalesConfigurableBundleWidget\Checker\ConfiguredBundleCheckerInterface
     */
    public function createConfiguredBundleChecker(): ConfiguredBundleCheckerInterface
    {
        return new ConfiguredBundleChecker($this->getMessenger());
    }

    /**
     * @return \SprykerShop\Yves\SalesConfigurableBundleWidget\Dependency\Client\SalesConfigurableBundleWidgetToMessengerClientInterface
     */
    public function getMessenger(): SalesConfigurableBundleWidgetToMessengerClientInterface
    {
        return $this->getProvidedDependency(SalesConfigurableBundleWidgetDependencyProvider::CLIENT_MESSENGER);
    }
}
