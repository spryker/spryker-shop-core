<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithExternalRedirectInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface;
use Symfony\Component\HttpFoundation\Request;

class CustomerStep extends AbstractBaseStep implements StepWithBreadcrumbInterface, StepWithExternalRedirectInterface
{
    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface
     */
    protected $customerStepHandler;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var string
     */
    protected $logoutRoute;

    /**
     * @var string
     */
    protected $externalRedirect;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface $customerClient
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface $customerStepHandler
     * @param string $stepRoute
     * @param string|null $escapeRoute
     * @param string $logoutRoute
     */
    public function __construct(
        CheckoutPageToCustomerClientInterface $customerClient,
        StepHandlerPluginInterface $customerStepHandler,
        $stepRoute,
        $escapeRoute,
        $logoutRoute
    ) {
        parent::__construct($stepRoute, $escapeRoute);

        $this->customerClient = $customerClient;
        $this->customerStepHandler = $customerStepHandler;
        $this->logoutRoute = $logoutRoute;
    }

    /**
     * Require input for customer authentication if the customer is not logged in already, or haven't authenticated yet.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer)
    {
        if ($this->isCustomerLoggedIn()) {
            return false;
        }

        return true;
    }

    /**
     * Update QuoteTransfer with customer step handler plugin.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        return $this->customerStepHandler->addToDataClass($request, $quoteTransfer);
    }

    /**
     * The customer step is considered done (return true) if he QuoteTransfer contains a non empty CustomerTransfer.
     * If the CustomerTransfer is guest and the customer is logged in, then we override the guest customer with the
     * logged in customer, e.g. return false and execute() will do the rest.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer)
    {
        if ($this->isCustomerInQuote($quoteTransfer) === false) {
            return false;
        }

        if ($this->isGuestCustomerSelected($quoteTransfer)) {
            if ($this->isCustomerLoggedIn()) {
                // override guest user with logged in user
                return false;
            }

            return true;
        }

        $customerTransfer = $this->customerClient->getCustomer();
        if (!$customerTransfer) {
            return false;
        }

        $customerTransfer = $this->customerClient->findCustomerById($customerTransfer);
        if (!$customerTransfer) {
            $this->externalRedirect = $this->logoutRoute;

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isCustomerInQuote(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getCustomer() !== null;
    }

    /**
     * @return bool
     */
    protected function isCustomerLoggedIn()
    {
        return $this->customerClient->getCustomer() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isGuestCustomerSelected(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getCustomer()->getIsGuest();
    }

    /**
     * @return string
     */
    public function getBreadcrumbItemTitle()
    {
        return 'checkout.step.customer.title';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemEnabled(AbstractTransfer $quoteTransfer)
    {
        return $this->postCondition($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemHidden(AbstractTransfer $quoteTransfer)
    {
        return $this->isCustomerLoggedIn();
    }

    /**
     * Return external redirect url, when redirect occurs not within same application. Used after execute.
     *
     * @return string
     */
    public function getExternalRedirectUrl()
    {
        return $this->externalRedirect;
    }
}
