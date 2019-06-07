<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\Router\UrlMatcher;

use SprykerShop\Yves\RouterExtension\Dependency\Plugin\RouterEnhancerAwareInterface;
use Symfony\Component\Routing\Matcher\CompiledUrlMatcher;

class UrlMatcher extends CompiledUrlMatcher implements RouterEnhancerAwareInterface
{
    /**
     * @var \SprykerShop\Yves\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface[]
     */
    protected $routerEnhancerPlugins;

    /**
     * @param array $routerEnhancerPlugins
     *
     * @return void
     */
    public function setRouterEnhancerPlugins(array $routerEnhancerPlugins): void
    {
        $this->routerEnhancerPlugins = $routerEnhancerPlugins;
    }

    /**
     * @param string $pathinfo
     *
     * @return array
     */
    public function match($pathinfo)
    {
        foreach ($this->routerEnhancerPlugins as $routerEnhancerPlugin) {
            $pathinfo = $routerEnhancerPlugin->beforeMatch($pathinfo, $this->getContext());
        }

        $parameters = parent::match($pathinfo);

        foreach ($this->routerEnhancerPlugins as $routerEnhancerPlugin) {
            $parameters = $routerEnhancerPlugin->afterMatch($parameters, $this->getContext());
        }

        return $parameters;
    }
}
