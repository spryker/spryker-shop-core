<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\CheckoutStep;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\AbstractBaseStep;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface;
use Symfony\Component\HttpFoundation\Request;

class SaveRequestForQuoteStep extends AbstractBaseStep implements StepWithBreadcrumbInterface
{
    /**
     * Requirements for this step, return true when satisfied.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function preCondition(AbstractTransfer $quoteTransfer): bool
    {
        return (bool)count($quoteTransfer->getItems());
    }

    /**
     * Require input, should we render view with form or just skip step after calling execute.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $dataTransfer): bool
    {
        return true;
    }

    /**
     * Execute step logic, happens after form submit if provided, gets AbstractTransfer filled by form data.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, AbstractTransfer $dataTransfer)
    {
        return $dataTransfer;
    }

    /**
     * Conditions that should be met for this step to be marked as completed. returns true when satisfied.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $dataTransfer): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getBreadcrumbItemTitle(): string
    {
        return 'Save RFQ';// ToDo glossary
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemEnabled(AbstractTransfer $dataTransfer): bool
    {
        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemHidden(AbstractTransfer $dataTransfer): bool
    {
        return false;
    }
}
