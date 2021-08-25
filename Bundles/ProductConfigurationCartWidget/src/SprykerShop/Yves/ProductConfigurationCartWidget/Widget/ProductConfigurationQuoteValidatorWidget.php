<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationCartWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetConfig getConfig()
 */
class ProductConfigurationQuoteValidatorWidget extends AbstractWidget
{
    protected const PARAMETER_IS_QUOTE_PRODUCT_CONFIGURATION_VALID = 'isQuoteProductConfigurationValid';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->addIsQuoteProductConfigurationValidParameter($quoteTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductConfigurationQuoteValidatorWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductConfigurationCartWidget/views/product-quote-configuration/product-quote-configuration.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addIsQuoteProductConfigurationValidParameter(QuoteTransfer $quoteTransfer): void
    {
        $isQuoteProductConfigurationValid = $this->getFactory()
            ->getProductConfigurationCartClient()
            ->isQuoteProductConfigurationValid($quoteTransfer);

        $this->addParameter(static::PARAMETER_IS_QUOTE_PRODUCT_CONFIGURATION_VALID, $isQuoteProductConfigurationValid);
    }
}
