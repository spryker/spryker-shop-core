<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutWidget\Plugin\CartPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StepBreadcrumbsTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\CheckoutWidget\CheckoutBreadcrumbWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\CheckoutWidget\CheckoutWidgetFactory getFactory()
 */
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
        return '@CheckoutWidget/_cart-page/checkout-breadcrumbs.twig';
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
