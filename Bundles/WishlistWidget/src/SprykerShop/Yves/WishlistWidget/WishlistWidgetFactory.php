<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\WishlistWidget\Dependency\Client\WishlistWidgetToWishlistClientInterface;

class WishlistWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\WishlistWidget\Dependency\Client\WishlistWidgetToWishlistClientInterface
     */
    public function getWishlistClient(): WishlistWidgetToWishlistClientInterface
    {
        return $this->getProvidedDependency(WishlistWidgetDependencyProvider::CLIENT_WISHLIST);
    }
}
