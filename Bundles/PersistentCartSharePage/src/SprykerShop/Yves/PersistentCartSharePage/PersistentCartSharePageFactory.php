<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartSharePage;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Kernel\Application;
use SprykerShop\Yves\PersistentCartSharePage\Dependency\Client\PersistentCartSharePageToPersistentCartShareClientInterface;

class PersistentCartSharePageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\PersistentCartSharePage\Dependency\Client\PersistentCartSharePageToPersistentCartShareClientInterface
     */
    public function getPersistentCartShareClient(): PersistentCartSharePageToPersistentCartShareClientInterface
    {
        return $this->getProvidedDependency(PersistentCartSharePageDependencyProvider::CLIENT_PERSISTENT_CART_SHARE);
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(PersistentCartSharePageDependencyProvider::PLUGIN_APPLICATION);
    }
}
