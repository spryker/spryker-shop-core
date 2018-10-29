<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StepBreadcrumbsTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CheckoutWidget\CheckoutWidgetFactory getFactory()
 */
class CheckoutBreadcrumbWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->addParameter('stepBreadcrumbs', $this->getStepBreadcrumbs($quoteTransfer));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CheckoutBreadcrumbWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CheckoutWidget/views/cart-checkout-breadcrumb/cart-checkout-breadcrumb.twig';
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
