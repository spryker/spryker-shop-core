<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithCodeInterface;
use SprykerShop\Yves\CheckoutPage\CheckoutPageConfig;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface;
use Symfony\Component\HttpFoundation\Request;

class AddressStep extends AbstractBaseStep implements StepWithBreadcrumbInterface, StepWithCodeInterface
{
    /**
     * @var string
     */
    protected const STEP_CODE = 'address';

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface
     */
    protected $calculationClient;

    /**
     * @var array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutAddressStepEnterPreCheckPluginInterface>
     */
    protected $checkoutAddressStepEnterPreCheckPlugins;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Process\Steps\StepExecutorInterface
     */
    protected $stepExecutor;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface
     */
    protected $postConditionChecker;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig
     */
    protected $checkoutPageConfig;

    /**
     * @var list<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutAddressStepPostExecutePluginInterface>
     */
    protected array $checkoutAddressStepPostExecutePlugins;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface $calculationClient
     * @param \SprykerShop\Yves\CheckoutPage\Process\Steps\StepExecutorInterface $stepExecutor
     * @param \SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface $postConditionChecker
     * @param \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig $checkoutPageConfig
     * @param string $stepRoute
     * @param string|null $escapeRoute
     * @param array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutAddressStepEnterPreCheckPluginInterface> $checkoutAddressStepEnterPreCheckPlugins
     * @param list<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutAddressStepPostExecutePluginInterface> $checkoutAddressStepPostExecutePlugins
     */
    public function __construct(
        CheckoutPageToCalculationClientInterface $calculationClient,
        StepExecutorInterface $stepExecutor,
        PostConditionCheckerInterface $postConditionChecker,
        CheckoutPageConfig $checkoutPageConfig,
        $stepRoute,
        $escapeRoute,
        array $checkoutAddressStepEnterPreCheckPlugins,
        array $checkoutAddressStepPostExecutePlugins
    ) {
        parent::__construct($stepRoute, $escapeRoute);

        $this->calculationClient = $calculationClient;
        $this->stepExecutor = $stepExecutor;
        $this->postConditionChecker = $postConditionChecker;
        $this->checkoutPageConfig = $checkoutPageConfig;
        $this->checkoutAddressStepEnterPreCheckPlugins = $checkoutAddressStepEnterPreCheckPlugins;
        $this->checkoutAddressStepPostExecutePlugins = $checkoutAddressStepPostExecutePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer)
    {
        return $this->executeCheckoutAddressStepEnterPreCheckPlugins($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function preCondition(AbstractTransfer $quoteTransfer)
    {
        return parent::preCondition($quoteTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        if (!$this->executeCheckoutAddressStepEnterPreCheckPlugins($quoteTransfer)) {
            return $quoteTransfer;
        }
        $quoteTransfer = $this->stepExecutor->execute($request, $quoteTransfer);

        $quoteTransfer = $this->executeCheckoutAddressStepPostExecutePlugins($quoteTransfer)->getQuoteTransfer();

        return $this->calculationClient->recalculate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer)
    {
        return $this->postConditionChecker->check($quoteTransfer);
    }

    /**
     * @return string
     */
    public function getBreadcrumbItemTitle()
    {
        return 'checkout.step.address.title';
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
        return !$this->requireInput($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function executeCheckoutAddressStepEnterPreCheckPlugins(AbstractTransfer $quoteTransfer): bool
    {
        foreach ($this->checkoutAddressStepEnterPreCheckPlugins as $checkoutAddressStepEnterPreCheckPlugin) {
            if (!$checkoutAddressStepEnterPreCheckPlugin->check($quoteTransfer)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return static::STEP_CODE;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function executeCheckoutAddressStepPostExecutePlugins(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = (new QuoteResponseTransfer())
            ->setQuoteTransfer($quoteTransfer)
            ->setIsSuccessful(true);

        foreach ($this->checkoutAddressStepPostExecutePlugins as $checkoutAddressStepPostExecutePlugin) {
            $quoteResponseTransfer = $checkoutAddressStepPostExecutePlugin->execute($quoteTransfer);

            if (!$quoteResponseTransfer->getIsSuccessfulOrFail()) {
                return $quoteResponseTransfer;
            }
        }

        return $quoteResponseTransfer;
    }
}
