<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Form\DataProvider;

interface QuoteFormDataProviderInterface
{
    /**
     * @param null|string $quoteName
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData($quoteName = null);
}
