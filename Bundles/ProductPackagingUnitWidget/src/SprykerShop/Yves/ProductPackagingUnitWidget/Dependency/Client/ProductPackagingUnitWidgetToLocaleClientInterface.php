<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget\Dependency\Client;

interface ProductPackagingUnitWidgetToLocaleClientInterface
{
    /**
     * @return string
     */
    public function getCurrentLocale(): string;
}
