<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopPermission;

use Spryker\Yves\Kernel\AbstractFactory;

class ShopPermissionFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ShopPermission\Dependency\Client\ShopPermissionToPermissionClientInterface
     */
    public function getPermissionClient()
    {
        return $this->getProvidedDependency(ShopPermissionDependencyProvider::CLIENT_PERMISSION);
    }

    /**
     * @return \Spryker\Yves\Twig\Plugin\TwigFunctionPluginInterface[]
     */
    public function getPermissionTwigFunctionPlugins()
    {
        return $this->getProvidedDependency(ShopPermissionDependencyProvider::PERMISSION_TWIG_FUNCTION_PLUGINS);
    }

    /**
     * @return \SprykerShop\Yves\ShopApplication\Plugin\AbstractTwigExtensionPlugin[]|\Twig\Extension\ExtensionInterface[]
     */
    public function getPermissionTwigExtensionPlugins(): array
    {
        return $this->getProvidedDependency(ShopPermissionDependencyProvider::PERMISSION_TWIG_EXTENSION_PLUGINS);
    }
}
