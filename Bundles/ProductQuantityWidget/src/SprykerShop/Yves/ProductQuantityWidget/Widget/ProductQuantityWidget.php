<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuantityWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductQuantityWidget\ProductQuantityWidgetFactory getFactory()
 */
class ProductQuantityWidget extends AbstractWidget
{
    /**
     * @param float $quantity
     * @param int $idProductConcrete
     * @param string $sku
     * @param string $groupKey
     */
    public function __construct(float $quantity, int $idProductConcrete, string $sku, string $groupKey)
    {
        $this->addParameter('quantity', $quantity)
            ->addParameter('sku', $sku)
            ->addParameter('groupKey', $groupKey);
        $this->setQuantityRestrictions($idProductConcrete);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    protected function setQuantityRestrictions(int $idProductConcrete): void
    {
        $productQuantityStorageTransfer = $this->getFactory()
            ->getProductQuantityStorageClient()
            ->getProductQuantityStorage($idProductConcrete);

        $this->addParameter('productQuantityStorage', $productQuantityStorageTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductQuantityWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductQuantityWidget/views/product-quantity-widget/product-quantity-widget.twig';
    }
}
