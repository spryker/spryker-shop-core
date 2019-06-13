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
class PdpProductQuantityRestrictionWidget extends AbstractWidget
{
    /**
     * @param int $idProductConcrete
     */
    public function __construct(int $idProductConcrete)
    {
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
            ->createQuantityRestrictionReader()
            ->getQuantityRestrictions($idProductConcrete);

        $this->addParameter('productQuantityStorage', $productQuantityStorageTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'PdpProductQuantityRestrictionWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductQuantityRestrictionWidget/views/pdp-product-quantity-restriction-widget/pdp-product-quantity-restriction-widget.twig';
    }
}
