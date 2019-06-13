<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuantityRestrictionWidget\Widget;

class PdpProductQuantityRestrictionWidget extends ProductQuantityRestrictionWidget
{
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
