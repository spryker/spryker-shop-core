<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class ProductViewAvailabilityWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $this->addParameter('product', $productViewTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductViewAvailabilityWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@AvailabilityWidget/views/availability/availability.twig';
    }
}
