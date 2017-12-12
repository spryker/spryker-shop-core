<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopLayout;

use Spryker\Yves\Kernel\AbstractFactory;

class ShopLayoutFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ShopLayoutDependencyProvider::STORE);
    }
}
