<?php

namespace SprykerShop\Yves\SharedCartPage\Plugin\ResourceShare;

use Generated\Shared\Transfer\ResourceShareTransfer;
use Generated\Shared\Transfer\RouteTransfer;
use SprykerShop\Yves\ResourceSharePageExtension\Dependency\Plugin\ResourceShareRouterStrategyPluginInterface;

class SharedCartRouterStrategyPlugin implements ResourceShareRouterStrategyPluginInterface
{
    /**
     * @uses \SprykerShop\Yves\MultiCartPage\Plugin\Provider\MultiCartPageControllerProvider::ROUTE_MULTI_CART_INDEX
     */
    protected const ROUTE_MULTI_CART_INDEX = 'multi-cart';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::QUOTE_RESOURCE_TYPE
     */
    protected const QUOTE_RESOURCE_TYPE = 'quote';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::KEY_SHARE_OPTION
     */
    protected const KEY_SHARE_OPTION = 'share_option';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::PERMISSION_GROUP_READ_ONLY
     */
    protected const PERMISSION_GROUP_READ_ONLY = 'READ_ONLY';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::PERMISSION_GROUP_FULL_ACCESS
     */
    protected const PERMISSION_GROUP_FULL_ACCESS = 'FULL_ACCESS';

    /**
     * {@inheritdoc}
     * - Returns 'true', when resource type is Quote, and share option is Read-only or Full access.
     * - Returns 'false' otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return bool
     */
    public function isApplicable(ResourceShareTransfer $resourceShareTransfer): bool
    {
        $resourceShareTransfer->requireResourceType();
        if ($resourceShareTransfer->getResourceType() !== static::QUOTE_RESOURCE_TYPE) {
            return false;
        }

        $resourceShareTransfer->requireResourceShareData();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        $shareOption = $resourceShareDataTransfer->getData()[static::KEY_SHARE_OPTION] ?? null;
        if (!$shareOption) {
            return false;
        }

        return in_array($shareOption, [static::PERMISSION_GROUP_READ_ONLY, static::PERMISSION_GROUP_FULL_ACCESS], true);
    }

    /**
     * {@inheritdoc}
     * - Returns RouteTransfer with a cart route.
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
            ->setRoute(static::ROUTE_MULTI_CART_INDEX);
    }
}
