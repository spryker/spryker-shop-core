<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductNewPage\Dependency\Service;

use Generated\Shared\Transfer\NumberFormatConfigTransfer;

interface ProductNewPageToUtilNumberServiceInterface
{
    /**
     * @param string|null $locale
     *
     * @return \Generated\Shared\Transfer\NumberFormatConfigTransfer
     */
    public function getNumberFormatConfig(?string $locale = null): NumberFormatConfigTransfer;
}
