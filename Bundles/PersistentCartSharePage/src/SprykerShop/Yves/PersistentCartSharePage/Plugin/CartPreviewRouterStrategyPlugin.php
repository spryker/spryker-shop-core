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
    protected const SHARE_OPTION_PREVIEW = 'PREVIEW';
    protected const RESOURCE_TYPE_QUOTE = 'quote';
    protected const REDIRECT_URL_PATTERN = 'quote';
    protected const ROUTE_CART_PREVIEW = 'cart/preview';
    protected const PARAM_RESOURCE_SHARE_UUID = "resourceShareUuid";

    /**
     * {@inheritdoc}
     * - Checks if this plugin is applicable to work with quote resource share.
     * - Checks if this plugin is applicable to work with preview share option.
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

        $resourceShareData = $resourceShareTransfer
            ->requireResourceShareData()->getResourceShareData();

        if (!$resourceShareTransfer->getUuid() || !$resourceShareData->getShareOption()) {
            return false;
        }

        if ($resourceShareData->getShareOption() !== static::SHARE_OPTION_PREVIEW) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     * - Returns route for the cart preview share.
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
