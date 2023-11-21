<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesServicePointWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\SalesServicePointWidget\Sanitizer\ItemSanitizer;
use SprykerShop\Yves\SalesServicePointWidget\Sanitizer\ItemSanitizerInterface;

class SalesServicePointWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\SalesServicePointWidget\Sanitizer\ItemSanitizerInterface
     */
    public function createItemSanitizer(): ItemSanitizerInterface
    {
        return new ItemSanitizer();
    }
}
