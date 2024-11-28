<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Expander;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteCustomerExpanderInterface
{
 /**
  * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
  * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
  *
  * @return \Generated\Shared\Transfer\QuoteTransfer
  */
    public function expandQuoteWithCustomerData(
        QuoteTransfer $quoteTransfer,
        CustomerTransfer $customerTransfer
    ): QuoteTransfer;
}
