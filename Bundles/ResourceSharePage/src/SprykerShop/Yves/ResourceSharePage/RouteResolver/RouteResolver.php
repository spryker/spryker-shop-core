<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\RouteResolver;

use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\RouteTransfer;

class RouteResolver implements RouteResolverInterface
{
    /**
     * @var \SprykerShop\Yves\ResourceSharePageExtension\Dependency\Plugin\ResourceShareRouterStrategyPluginInterface[]
     */
    protected $resourceShareRouterStrategyPlugins;

    /**
     * @param \SprykerShop\Yves\ResourceSharePageExtension\Dependency\Plugin\ResourceShareRouterStrategyPluginInterface[] $resourceShareRouterStrategyPlugins
     */
    public function __construct(array $resourceShareRouterStrategyPlugins)
    {
        $this->resourceShareRouterStrategyPlugins = $resourceShareRouterStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RouteTransfer|null
     */
    public function resolveRoute(ResourceShareResponseTransfer $resourceShareResponseTransfer): ?RouteTransfer
    {
        foreach ($this->resourceShareRouterStrategyPlugins as $resourceShareRouterStrategyPlugin) {
            if (!$resourceShareRouterStrategyPlugin->isApplicable($resourceShareResponseTransfer->getResourceShare())) {
                continue;
            }

            return $resourceShareRouterStrategyPlugin->resolveRoute($resourceShareResponseTransfer->getResourceShare());
        }

        return null;
    }
}
