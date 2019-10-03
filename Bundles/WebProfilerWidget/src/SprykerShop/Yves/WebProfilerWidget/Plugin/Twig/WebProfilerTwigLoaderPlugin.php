<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WebProfilerWidget\Plugin\Twig;

use Spryker\Shared\Twig\Loader\FilesystemLoaderInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigLoaderPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\WebProfilerWidget\WebProfilerWidgetFactory getFactory()
 * @method \SprykerShop\Yves\WebProfilerWidget\WebProfilerWidgetConfig getConfig()
 */
class WebProfilerTwigLoaderPlugin extends AbstractPlugin implements TwigLoaderPluginInterface
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
        return $this->getFactory()->createTwigFilesystemLoader();
    }
}
