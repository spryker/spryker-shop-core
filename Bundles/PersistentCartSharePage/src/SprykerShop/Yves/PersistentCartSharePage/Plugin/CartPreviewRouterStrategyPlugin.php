<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartSharePage\Plugin;

use Generated\Shared\Transfer\ResourceShareTransfer;
use Generated\Shared\Transfer\RouteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ResourceSharePageExtension\Dependency\Plugin\ResourceShareRouterStrategyPluginInterface;

/**
 * @method \SprykerShop\Yves\PersistentCartSharePage\PersistentCartSharePageFactory getFactory()
 */
class CartPreviewRouterStrategyPlugin extends AbstractPlugin implements ResourceShareRouterStrategyPluginInterface
{
    /**
     * @see \Spryker\Zed\PersistentCartShare\PersistentCartShareConfig::SHARE_OPTION_PREVIEW
     */
    protected const SHARE_OPTION_PREVIEW = 'PREVIEW';

    /**
     * @see \Spryker\Zed\PersistentCartShare\PersistentCartShareConfig::RESOURCE_TYPE_QUOTE
     */
    protected const RESOURCE_TYPE_QUOTE = 'quote';

    /**
     * @uses \SprykerShop\Yves\PersistentCartSharePage\Plugin\Provider\PersistentCartSharePageControllerProvider::ROUTE_CART_PREVIEW
     */
    protected const ROUTE_CART_PREVIEW = 'cart/preview';
    protected const PARAM_RESOURCE_SHARE_UUID = 'resourceShareUuid';

    /**
     * {@inheritdoc}
     * - Returns true if resource type is 'quote' and cart share option is 'PREVIEW'.
     * - Returns false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return bool
     */
    public function isApplicable(ResourceShareTransfer $resourceShareTransfer): bool
    {
        if ($resourceShareTransfer->getResourceType() !== static::RESOURCE_TYPE_QUOTE) {
            return false;
        }

        $resourceShareTransfer->requireResourceShareData();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        return $resourceShareTransfer->getUuid() && $resourceShareDataTransfer->getShareOption() === static::SHARE_OPTION_PREVIEW;
    }

    /**
     * {@inheritdoc}
     * - Returns route for the cart preview share page.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\RouteTransfer
     */
    public function resolveRoute(ResourceShareTransfer $resourceShareTransfer): RouteTransfer
    {
        return (new RouteTransfer())
            ->setRoute(static::ROUTE_CART_PREVIEW)
            ->setParameters([
                static::PARAM_RESOURCE_SHARE_UUID => $resourceShareTransfer->getUuid(),
            ]);
    }
}
