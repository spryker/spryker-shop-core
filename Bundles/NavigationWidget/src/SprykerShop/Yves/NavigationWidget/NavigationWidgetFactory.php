<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NavigationWidget;

use Spryker\Yves\Kernel\AbstractFactory;

class NavigationWidgetFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\NavigationStorage\NavigationStorageClientInterface
     */
    public function getNavigationStorageClient()
    {
        return $this->getProvidedDependency(NavigationWidgetDependencyProvider::CLIENT_NAVIGATION_STORAGE);
    }
}
