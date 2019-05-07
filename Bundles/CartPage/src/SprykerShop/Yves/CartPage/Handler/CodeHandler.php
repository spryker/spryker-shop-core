<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Handler;

use Generated\Shared\Transfer\CodeCalculationResultTransfer;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCalculationClientInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToMessengerClientInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToQuoteClientInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToZedRequestClientInterface;

// TODO: reduce dependencies somehow
class CodeHandler implements CodeHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCalculationClientInterface
     */
    protected $calculationClient;

    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToMessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @var \SprykerShop\Yves\CartPageExtension\Dependency\Plugin\CodeHandlerPluginInterface[]
     */
    protected $codeHandlerPlugins;

    /**
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCalculationClientInterface $calculationClient
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToQuoteClientInterface $quoteClient
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToZedRequestClientInterface $zedRequestClient
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToMessengerClientInterface $messengerClient
     * @param \SprykerShop\Yves\CartPageExtension\Dependency\Plugin\CodeHandlerPluginInterface[] $codeHandlers
     */
    public function __construct(
        CartPageToCartClientInterface $cartClient,
        CartPageToCalculationClientInterface $calculationClient,
        CartPageToQuoteClientInterface $quoteClient,
        CartPageToZedRequestClientInterface $zedRequestClient,
        CartPageToMessengerClientInterface $messengerClient,
        array $codeHandlers
    ) {
        $this->cartClient = $cartClient;
        $this->calculationClient = $calculationClient;
        $this->quoteClient = $quoteClient;
        $this->zedRequestClient = $zedRequestClient;
        $this->messengerClient = $messengerClient;
        $this->codeHandlerPlugins = $codeHandlers;
    }

    /**
     * @param string $code
     *
     * @return void
     */
    public function add($code)
    {
        $quoteTransfer = $this->cartClient->getQuote();

        foreach ($this->codeHandlerPlugins as $codeHandler) {
            $codeHandler->addCandidate($quoteTransfer, $code);
        }

        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);
        $this->quoteClient->setQuote($quoteTransfer);

        foreach ($this->codeHandlerPlugins as $codeHandler) {
            $codeCalculationResult = $codeHandler->getCodeRecalculationResult($quoteTransfer, $code);

            if ($codeCalculationResult->getIsSuccess()) {
                $this->messengerClient->addSuccessMessage($codeHandler->getSuccessMessage($quoteTransfer, $code));
                return;
            }

            if ($this->hasErrors($codeCalculationResult)) {
                $this->addErrors($codeCalculationResult);
                return;
            }
        }

        $this->handleCodeApplicationFailure();
        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();
    }

    /**
     * @param string $code
     *
     * @return void
     */
    public function remove($code)
    {
        $quoteTransfer = $this->cartClient->getQuote();

        foreach ($this->codeHandlerPlugins as $codeHandler) {
            $codeHandler->removeCode($quoteTransfer, $code);
        }

        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);

        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();
        $this->quoteClient->setQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function clear()
    {
        $quoteTransfer = $this->cartClient->getQuote();

        foreach ($this->codeHandlerPlugins as $codeHandler) {
            $codeHandler->clearQuote($quoteTransfer);
        }

        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);

        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();
        $this->quoteClient->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CodeCalculationResultTransfer $calculationResult
     *
     * @return void
     */
    protected function addErrors(CodeCalculationResultTransfer $calculationResult)
    {
        foreach ($calculationResult->getErrors() as $error) {
            $this->messengerClient->addErrorMessage($error->getMessage());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CodeCalculationResultTransfer $calculationResultTransfer
     *
     * @return bool
     */
    protected function hasErrors(CodeCalculationResultTransfer $calculationResultTransfer)
    {
        return count($calculationResultTransfer->getErrors()) > 0;
    }

    /**
     * @return void
     */
    protected function handleCodeApplicationFailure()
    {
        $this->messengerClient->addErrorMessage('cart.code.apply.failed');
    }
}
