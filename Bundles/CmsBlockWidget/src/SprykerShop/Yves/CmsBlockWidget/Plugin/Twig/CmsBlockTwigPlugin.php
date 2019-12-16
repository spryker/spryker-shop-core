<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use SprykerShop\Yves\ShopApplication\Plugin\AbstractTwigExtensionPlugin;
use Twig\Environment;

/**
 * @method \SprykerShop\Yves\CmsBlockWidget\CmsBlockWidgetFactory getFactory()
 */
class CmsBlockTwigPlugin extends AbstractTwigExtensionPlugin
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
        return $this->registerCmsBlockTwigExtensions($twig);
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function registerCmsBlockTwigExtensions(Environment $twig): Environment
    {
        $twig->setExtensions($this->getFactory()->getTwigExtensionPlugins());

        return $twig;
    }
}
