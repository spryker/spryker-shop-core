<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface CodeHandlerPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return void
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, $code);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return bool
     */
    public function hasCandidate(QuoteTransfer $quoteTransfer, $code);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return void
     */
    public function removeCode(QuoteTransfer $quoteTransfer, $code);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\CodeCalculationResultTransfer
     */
    public function getCodeRecalculationResult(QuoteTransfer $quoteTransfer, $code);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return string
     */
    public function getSuccessMessage(QuoteTransfer $quoteTransfer, $code);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function clearQuote(QuoteTransfer $quoteTransfer);
}
