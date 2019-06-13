<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuantityRestrictionWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductQuantityRestrictionWidget\ProductQuantityRestrictionWidgetFactory getFactory()
 */
class ProductQuantityRestrictionWidget extends AbstractWidget
{
    /**
     * @param int $fkProductConcrete
     */
    public function __construct(int $fkProductConcrete)
    {
        $this->setQuantityRestrictions($fkProductConcrete);
    }

    /**
     * @param int $fkProductConcrete
     *
     * @return void
     */
    protected function setQuantityRestrictions(int $fkProductConcrete): void
    {
        $productQuantityStorageTransfer = $this->getFactory()
            ->createQuantityRestrictionReader()
            ->getQuantityRestrictions($fkProductConcrete);

        $this->addParameter('productQuantityStorage', $productQuantityStorageTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductQuantityRestrictionWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductQuantityRestrictionWidget/views/product-quantity-restriction-widget/product-quantity-restriction-widget.twig';
    }
}
