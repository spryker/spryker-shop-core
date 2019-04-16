<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToResourceShareClientInterface;

class ResourceSharePageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ResourceSharePageExtension\Dependency\Plugin\ResourceShareRouterStrategyPluginInterface[]
     */
    public function getResourceShareRouterStrategyPlugin(): array
    {
        return $this->getProvidedDependency(ResourceSharePageDependencyProvider::PLUGINS_RESOURCE_SHARE_ROUTER_STRATEGY);
    }

    /**
     * @return \SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToResourceShareClientInterface
     */
    public function getResourceShareClient(): ResourceSharePageToResourceShareClientInterface
    {
        return $this->getProvidedDependency(ResourceSharePageDependencyProvider::CLIENT_RESOURCE_SHARE);
    }
}
