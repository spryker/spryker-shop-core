<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\QuoteRequestWidget\QuoteRequestWidgetFactory getFactory()
 */
class QuoteRequestActionsWidget extends AbstractWidget
{
    protected const PARAMETER_IS_VISIBLE = 'isVisible';
    protected const PARAMETER_BACK_URL = 'backUrl';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $backUrl
     */
    public function __construct(QuoteTransfer $quoteTransfer, string $backUrl)
    {
        $this->addIsVisibleParameter($quoteTransfer);
        $this->addBackUrlParameter($backUrl);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'QuoteRequestCheckoutWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@QuoteRequestWidget/views/quote-request-checkout/quote-request-checkout.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addIsVisibleParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(
            static::PARAMETER_IS_VISIBLE,
            $this->getFactory()->getQuoteRequestClient()->isEditableQuoteRequestVersion($quoteTransfer)
        );
    }

    /**
     * @param string $backUrl
     *
     * @return void
     */
    protected function addBackUrlParameter(string $backUrl): void
    {
        $this->addParameter(static::PARAMETER_BACK_URL, $backUrl);
    }
}
