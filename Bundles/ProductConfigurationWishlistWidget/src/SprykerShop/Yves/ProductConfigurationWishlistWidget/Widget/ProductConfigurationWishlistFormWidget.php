<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWishlistWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductConfigurationWishlistWidget\ProductConfigurationWishlistWidgetFactory getFactory()
 */
class ProductConfigurationWishlistFormWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_HAS_PRODUCT_CONFIGURATION_ATTACHED = 'hasProductConfigurationAttached';

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $this->addHasProductConfigurationAttachedParameter($productViewTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductConfigurationWishlistFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductConfigurationWishlistWidget/views/product-configuration-wishlist-form-widget/product-configuration-wishlist-form-widget.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function addHasProductConfigurationAttachedParameter(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter(
            static::PARAMETER_HAS_PRODUCT_CONFIGURATION_ATTACHED,
            $productViewTransfer->getProductConfigurationInstance() !== null ? 1 : 0,
        );
    }
}
