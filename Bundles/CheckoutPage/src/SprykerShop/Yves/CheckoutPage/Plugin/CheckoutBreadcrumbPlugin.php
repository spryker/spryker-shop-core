<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Plugin;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageFactory getFactory()
 * @method \Spryker\Client\Checkout\CheckoutClientInterface getClient()
 */
class CheckoutBreadcrumbPlugin extends AbstractPlugin
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $dataTransfer
     *
     * @return \Generated\Shared\Transfer\StepBreadcrumbsTransfer
     */
    public function generateStepBreadcrumbs(?AbstractTransfer $dataTransfer = null)
    {
        $stepFactory = $this->getFactory()->createStepFactory();
        $stepCollection = $stepFactory->createStepResolver()->resolveSteps();

        return $stepFactory->createStepBreadcrumbGenerator()->generateStepBreadcrumbs($stepCollection, $dataTransfer);
    }
}
