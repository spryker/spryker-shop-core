<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\RouteResolver;

use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\RouteTransfer;
use SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToMessengerClientInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RouteResolver implements RouteResolverInterface
{
    protected const GLOSSARY_KEY_RESOURCE_SHARE_LINK_ERROR_NO_ROUTE = 'resource-share.link.error.no-route';

    /**
     * @var \SprykerShop\Yves\ResourceSharePageExtension\Dependency\Plugin\ResourceShareRouterStrategyPluginInterface[]
     */
    protected $resourceShareRouterStrategyPlugins;

    /**
     * @var \SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToMessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @param \SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToMessengerClientInterface $messengerClient
     * @param \SprykerShop\Yves\ResourceSharePageExtension\Dependency\Plugin\ResourceShareRouterStrategyPluginInterface[] $resourceShareRouterStrategyPlugins
     */
    public function __construct(ResourceSharePageToMessengerClientInterface $messengerClient, array $resourceShareRouterStrategyPlugins)
    {
        $this->messengerClient = $messengerClient;
        $this->resourceShareRouterStrategyPlugins = $resourceShareRouterStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\RouteTransfer
     */
    public function resolveRoute(ResourceShareResponseTransfer $resourceShareResponseTransfer): RouteTransfer
    {
        $resourceShareResponseTransfer->requireResourceShare();

        foreach ($this->resourceShareRouterStrategyPlugins as $resourceShareRouterStrategyPlugin) {
            if (!$resourceShareRouterStrategyPlugin->isApplicable($resourceShareResponseTransfer->getResourceShare())) {
                continue;
            }

            return $resourceShareRouterStrategyPlugin->resolveRoute($resourceShareResponseTransfer->getResourceShare());
        }

        $this->messengerClient->addErrorMessage(static::GLOSSARY_KEY_RESOURCE_SHARE_LINK_ERROR_NO_ROUTE);

        throw new NotFoundHttpException();
    }
}
