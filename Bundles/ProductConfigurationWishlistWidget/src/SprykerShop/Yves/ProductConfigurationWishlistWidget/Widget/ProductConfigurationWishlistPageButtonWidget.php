<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWishlistWidget\Widget;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductConfigurationWishlistWidget\ProductConfigurationWishlistWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ProductConfigurationWishlistWidget\ProductConfigurationWishlistWidgetConfig getConfig()
 */
class ProductConfigurationWishlistPageButtonWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_VISIBLE = 'isVisible';

    /**
     * @var string
     */
    protected const PARAMETER_FORM = 'form';

    /**
     * @var string
     */
    protected const PARAMETER_PRODUCT_CONFIGURATOR_ROUTE_NAME = 'productConfiguratorRouteName';

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     */
    public function __construct(WishlistItemTransfer $wishlistItemTransfer)
    {
        $this->addIsVisibleParameter($wishlistItemTransfer);

        if (!$wishlistItemTransfer->getProductConfigurationInstance()) {
            return;
        }

        $this->addFormParameter($wishlistItemTransfer);
        $this->addProductConfigurationRouteNameParameter();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductConfigurationWishlistPageButtonWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductConfigurationWishlistWidget/views/wishlist-item-configuration-button/wishlist-item-configuration-button.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return void
     */
    protected function addIsVisibleParameter(WishlistItemTransfer $wishlistItemTransfer): void
    {
        $this->addParameter(static::PARAMETER_IS_VISIBLE, $wishlistItemTransfer->getProductConfigurationInstance() !== null);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return void
     */
    protected function addFormParameter(WishlistItemTransfer $wishlistItemTransfer): void
    {
        $productConfiguratorButtonFormCartPageDataProvider = $this->getFactory()
            ->createProductConfiguratorButtonFormDataProvider();

        $productConfigurationButtonForm = $this->getFactory()
            ->getProductConfigurationButtonForm()
            ->setData($productConfiguratorButtonFormCartPageDataProvider->getData($wishlistItemTransfer))
            ->createView();

        $this->addParameter(static::PARAMETER_FORM, $productConfigurationButtonForm);
    }

    /**
     * @return void
     */
    protected function addProductConfigurationRouteNameParameter(): void
    {
        $this->addParameter(
            static::PARAMETER_PRODUCT_CONFIGURATOR_ROUTE_NAME,
            $this->getConfig()->getProductConfiguratorGatewayRequestRoute(),
        );
    }
}
