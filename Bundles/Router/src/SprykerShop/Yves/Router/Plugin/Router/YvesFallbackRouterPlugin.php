<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\Router\Plugin\Router;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\RouterExtension\Dependency\Plugin\RouterPluginInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @method \SprykerShop\Yves\Router\RouterConfig getConfig()
 * @method \SprykerShop\Yves\Router\RouterFactory getFactory()
 */
class YvesFallbackRouterPlugin extends AbstractPlugin implements RouterPluginInterface
{
    /**
     * @api
     *
     * @return \Symfony\Component\Routing\RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->getFactory()->createYvesFallbackRouter();
    }
}
