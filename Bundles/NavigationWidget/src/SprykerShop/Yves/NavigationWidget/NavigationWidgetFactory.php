<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NavigationWidget;

use Spryker\Client\NavigationStorage\NavigationStorageClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class NavigationWidgetFactory extends AbstractFactory
{

    /**
     * @return NavigationStorageClientInterface
     */
    public function getNavigationStorageClient()
    {
        return $this->getProvidedDependency(NavigationWidgetDependencyProvider::CLIENT_NAVIGATION_STORAGE);
    }
}
