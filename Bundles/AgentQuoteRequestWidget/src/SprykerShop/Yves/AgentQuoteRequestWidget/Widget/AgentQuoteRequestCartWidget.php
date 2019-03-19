<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestWidget\AgentQuoteRequestWidgetFactory getFactory()
 * @method \SprykerShop\Yves\AgentQuoteRequestWidget\AgentQuoteRequestWidgetConfig getConfig()
 */
class AgentQuoteRequestCartWidget extends AbstractWidget
{
    protected const PARAMETER_FORM = 'form';
    protected const PARAMETER_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->addFormParameter();
        $this->addQuoteRequestReferenceParameter($quoteTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'AgentQuoteRequestCartWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@AgentQuoteRequestWidget/views/agent-quote-request-cart/agent-quote-request-cart.twig';
    }

    /**
     * @return void
     */
    protected function addFormParameter(): void
    {
        $this->addParameter(static::PARAMETER_FORM, $this->getFactory()->getAgentQuoteRequestCartForm()->createView());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addQuoteRequestReferenceParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(static::PARAMETER_QUOTE_REQUEST_REFERENCE, $quoteTransfer->getQuoteRequestReference());
    }
}
