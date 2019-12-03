<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceProductWidget\Widget;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\PriceProductWidget\PriceProductWidgetFactory getFactory()
 */
class PriceProductWidget extends AbstractWidget
{
    protected const PARAMETER_CURRENT_PRODUCT_PRICE = 'currentProductPrice';

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     * @param int|null $quantity
     */
    public function __construct(int $idProductConcrete, int $idProductAbstract, ?int $quantity)
    {
        if (!$quantity) {
            $quantity = 1;
        }

        $this->addPriceParameter($idProductConcrete, $idProductAbstract, $quantity);
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
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     * @param int $quantity
     *
     * @return void
     */
    protected function addPriceParameter(int $idProductConcrete, int $idProductAbstract, int $quantity): void
    {
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuantity($quantity)
            ->setIdProduct($idProductConcrete)
            ->setIdProductAbstract($idProductAbstract);

        $currentProductPriceTransfer = $this->getFactory()
            ->getPriceProductStorageClient()
            ->getResolvedCurrentProductPriceTransfer($priceProductFilterTransfer);

        $this->addParameter(static::PARAMETER_CURRENT_PRODUCT_PRICE, $currentProductPriceTransfer);
    }
}
