<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceProductWidget\Widget;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\PriceProductWidget\PriceProductWidgetFactory getFactory()
 */
class PriceProductWidget extends AbstractWidget
{
    protected const PARAMETER_CURRENT_PRODUCT_PRICE = 'currentProductPrice';

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        if (!$productViewTransfer->getQuantity()) {
            $productViewTransfer->setQuantity(1);
        }

        $this->addPriceParameter($productViewTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'PriceProductWidget';
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@PriceProductWidget/views/price/price.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function addPriceParameter(ProductViewTransfer $productViewTransfer): void
    {
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuantity($productViewTransfer->getQuantity())
            ->setIdProduct($productViewTransfer->getIdProductConcrete())
            ->setIdProductAbstract($productViewTransfer->getIdProductAbstract());

        $currentProductPriceTransfer = $this->getFactory()
            ->getPriceProductStorageClient()
            ->getResolvedCurrentProductPriceTransfer($priceProductFilterTransfer);

        $this->addParameter(static::PARAMETER_CURRENT_PRODUCT_PRICE, $currentProductPriceTransfer);
    }
}
