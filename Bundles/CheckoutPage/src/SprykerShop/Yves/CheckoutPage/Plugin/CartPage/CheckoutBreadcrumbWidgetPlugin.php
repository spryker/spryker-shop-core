<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Plugin\CartPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StepBreadcrumbsTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\CheckoutPage\CheckoutBreadcrumbWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageFactory getFactory()
 */
// TODO: this need to go to CheckoutWidget module
class CheckoutBreadcrumbWidgetPlugin extends AbstractWidgetPlugin implements CheckoutBreadcrumbWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter('stepBreadcrumbs', $this->getStepBreadcrumbs($quoteTransfer));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CheckoutPage/_cart-page/checkout-breadcrumbs.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\StepBreadcrumbsTransfer
     */
    protected function getStepBreadcrumbs(QuoteTransfer $quoteTransfer): StepBreadcrumbsTransfer
    {
        return $this->getFactory()
            ->getCheckoutBreadcrumbPlugin()
            ->generateStepBreadcrumbs($quoteTransfer);
    }
}
