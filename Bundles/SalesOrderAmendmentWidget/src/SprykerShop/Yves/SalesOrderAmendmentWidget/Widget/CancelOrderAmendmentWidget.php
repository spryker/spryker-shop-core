<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesOrderAmendmentWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\SalesOrderAmendmentWidget\SalesOrderAmendmentWidgetFactory getFactory()
 */
class CancelOrderAmendmentWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_FORM = 'form';

    /**
     * @var string
     */
    protected const PARAMETER_QUOTE = 'quote';

    /**
     * @var string
     */
    protected const PARAMETER_IS_VISIBLE = 'isVisible';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->addFormParameter();
        $this->addQuoteParameter($quoteTransfer);
        $this->addIsVisibleParameter($quoteTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CancelOrderAmendmentWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SalesOrderAmendmentWidget/views/cancel-order-amendment/cancel-order-amendment.twig';
    }

    /**
     * @return void
     */
    protected function addFormParameter(): void
    {
        $this->addParameter(static::PARAMETER_FORM, $this->getFactory()->getCancelOrderAmendmentForm()->createView());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addQuoteParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(static::PARAMETER_QUOTE, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addIsVisibleParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(static::PARAMETER_IS_VISIBLE, (bool)$quoteTransfer->getAmendmentOrderReference());
    }
}
