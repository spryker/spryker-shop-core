<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopPermission\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use SprykerShop\Yves\ShopApplication\Plugin\AbstractTwigExtensionPlugin;
use Twig\Environment;

/**
 * @method \SprykerShop\Yves\ShopPermission\ShopPermissionFactory getFactory()
 */
class ShopPermissionTwigPlugin extends AbstractTwigExtensionPlugin
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig = $this->registerPermissionTwigExtensions($twig);

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function registerPermissionTwigExtensions(Environment $twig): Environment
    {
        foreach ($this->getFactory()->getPermissionTwigExtensionPlugins() as $extensionPlugin) {
            $twig->addExtension($extensionPlugin);
        }

        return $twig;
    }
}
