<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FeaturedProductWidget;

use Pyz\Client\Catalog\CatalogClient;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductWidget\Plugin\FeaturedProductWidget\ProductWidgetPlugin;

class FeaturedProductWidgetFactory extends AbstractFactory
{

    /**
     * @return string[]
     */
    public function getFeaturedProductSubWidgetPlugins(): array
    {
        // TODO: get from dependency provider
        return [
            // TODO: get from project
            ProductWidgetPlugin::class,
        ];
    }

    public function getCatalogClient()
    {
        // TODO: get from dependency provider as bridge
        return new CatalogClient();
    }

}
