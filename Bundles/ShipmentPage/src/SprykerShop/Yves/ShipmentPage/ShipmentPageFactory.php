<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ShipmentPage\Sanitizer\ItemSanitizer;
use SprykerShop\Yves\ShipmentPage\Sanitizer\ItemSanitizerInterface;

class ShipmentPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ShipmentPage\Sanitizer\ItemSanitizerInterface
     */
    public function createItemSanitizer(): ItemSanitizerInterface
    {
        return new ItemSanitizer();
    }
}
