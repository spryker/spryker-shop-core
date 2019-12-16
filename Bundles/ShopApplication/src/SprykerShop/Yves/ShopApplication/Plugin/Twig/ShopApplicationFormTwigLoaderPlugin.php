<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin\Twig;

use Spryker\Shared\Twig\Loader\FilesystemLoaderInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigLoaderPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationFactory getFactory()
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationConfig getConfig()
 */
class ShopApplicationFormTwigLoaderPlugin extends AbstractPlugin implements TwigLoaderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Spryker\Shared\Twig\Loader\FilesystemLoaderInterface
     */
    public function getLoader(): FilesystemLoaderInterface
    {
        return $this->getFactory()->createFilesystemLoader();
    }
}
