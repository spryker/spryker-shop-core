<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HomePage;

use Spryker\Yves\Kernel\AbstractFactory;

class HomePageFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Catalog\CatalogClientInterface
     */
    public function getCatalogClient()
    {
        return $this->getProvidedDependency(HomePageDependencyProvider::CLIENT_CATALOG);
    }

    /**
     * @return string[]
     */
    public function getHomePageWidgetPlugins(): array
    {
        return [];
    }

}
