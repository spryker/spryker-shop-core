<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\RouteResolver;

use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\RouteTransfer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class RouteResolver implements RouteResolverInterface
{
    protected const MESSAGE_RESOURCE_SHARE_NO_ROUTE = 'resource-share.link.error.no-route';

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
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException
     *
     * @return \Generated\Shared\Transfer\RouteTransfer
     */
    public function resolveRoute(ResourceShareResponseTransfer $resourceShareResponseTransfer): RouteTransfer
    {
        if (!$resourceShareResponseTransfer->getResourceShare()) {
            throw new UnprocessableEntityHttpException();
        }

        foreach ($this->resourceShareRouterStrategyPlugins as $resourceShareRouterStrategyPlugin) {
            if (!$resourceShareRouterStrategyPlugin->isApplicable($resourceShareResponseTransfer->getResourceShare())) {
                continue;
            }

            return $resourceShareRouterStrategyPlugin->resolveRoute($resourceShareResponseTransfer->getResourceShare());
        }

        throw new NotFoundHttpException(static::MESSAGE_RESOURCE_SHARE_NO_ROUTE);
    }
}
