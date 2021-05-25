<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\ViewModel;

use Generated\Shared\Transfer\CartPageViewArgumentsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CartPage\CartPageConfig;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToQuoteClientInterface;
use SprykerShop\Yves\CartPage\Form\FormFactory;
use SprykerShop\Yves\CartPage\Model\CartItemReaderInterface;
use SprykerShop\Yves\CartPage\Plugin\Provider\AttributeVariantsProvider;
use Symfony\Component\Form\FormView;

class CartPageView implements CartPageViewInterface
{
    /**
     * @var \SprykerShop\Yves\CartPage\CartPageConfig
     */
    protected $config;

    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \SprykerShop\Yves\CartPage\Model\CartItemReaderInterface
     */
    protected $cartItemReader;

    /**
     * @var \SprykerShop\Yves\CartPage\Plugin\Provider\AttributeVariantsProvider
     */
    protected $attributesVariantsProvider;

    /**
     * @var \SprykerShop\Yves\CartPage\Form\FormFactory
     */
    protected $formFactory;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteResponseTransfer|null
     */
    protected $quoteResponseTransfer;

    /**
     * @param \SprykerShop\Yves\CartPage\CartPageConfig $config
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToQuoteClientInterface $quoteClient
     * @param \SprykerShop\Yves\CartPage\Model\CartItemReaderInterface $cartItemReader
     * @param \SprykerShop\Yves\CartPage\Plugin\Provider\AttributeVariantsProvider $attributesVariantsProvider
     * @param \SprykerShop\Yves\CartPage\Form\FormFactory $formFactory
     */
    public function __construct(
        CartPageConfig $config,
        CartPageToCartClientInterface $cartClient,
        CartPageToQuoteClientInterface $quoteClient,
        CartItemReaderInterface $cartItemReader,
        AttributeVariantsProvider $attributesVariantsProvider,
        FormFactory $formFactory
    ) {
        $this->config = $config;
        $this->cartClient = $cartClient;
        $this->quoteClient = $quoteClient;
        $this->cartItemReader = $cartItemReader;
        $this->attributesVariantsProvider = $attributesVariantsProvider;
        $this->formFactory = $formFactory;
    }

    /**
     * @param \Generated\Shared\Transfer\CartPageViewArgumentsTransfer $cartPageViewArgumentsTransfer
     *
     * @return array
     */
    public function getViewData(CartPageViewArgumentsTransfer $cartPageViewArgumentsTransfer): array
    {
        return [
            'cart' => $this->getQuote(),
            'cartItems' => $this->getItems(),
            'attributes' => $this->getAttributes(
                $cartPageViewArgumentsTransfer->getLocaleOrFail(),
                $cartPageViewArgumentsTransfer->getSelectedAttributes()
            ),
            'isQuoteEditable' => $this->isQuoteEditable(),
            'isQuoteLocked' => $this->isQuoteLocked(),
            'isQuoteValid' => $this->isQuoteValid(),
            'removeCartItemForm' => $this->getRemoveCartItemForm(),
        ];
    }

    /**
     * @return array
     */
    public function getCartTotalViewData(): array
    {
        return [
            'cart' => $this->getQuote(),
            'isQuoteEditable' => $this->isQuoteEditable(),
            'isQuoteValid' => $this->isQuoteValid(),

        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CartPageViewArgumentsTransfer $cartPageViewArgumentsTransfer
     *
     * @return array
     */
    public function getViewData2(CartPageViewArgumentsTransfer $cartPageViewArgumentsTransfer): array
    {
        return [
            'cart' => $this->getQuote(),
            'cartItems' => $this->getItems(),
            'attributes' => $this->getAttributes(
                $cartPageViewArgumentsTransfer->getLocaleOrFail(),
                $cartPageViewArgumentsTransfer->getSelectedAttributes()
            ),
            'isQuoteEditable' => $this->isQuoteEditable()
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuote(): QuoteTransfer
    {
        if ($this->quoteTransfer !== null) {
            return $this->quoteTransfer;
        }

        if ($this->config->isQuoteValidationEnabled()) {
            $this->quoteResponseTransfer = $this->cartClient->validateQuote();
            $this->quoteTransfer = $this->quoteResponseTransfer->getQuoteTransferOrFail();

            return $this->quoteTransfer;
        }

        $this->quoteTransfer = $this->cartClient->getQuote();

        return $this->quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getItems(): array
    {
        return $this->cartItemReader->getCartItems($this->getQuote());
    }

    /**
     * @param string $locale
     * @param array $selectedAttributes
     *
     * @return array
     */
    protected function getAttributes(string $locale, array $selectedAttributes): array
    {
        return $this->attributesVariantsProvider->getItemsAttributes(
            $this->getQuote(),
            $locale,
            $selectedAttributes
        );
    }

    /**
     * @return bool
     */
    protected function isQuoteEditable(): bool
    {
        return $this->quoteClient->isQuoteEditable($this->getQuote());
    }

    /**
     * @return bool
     */
    protected function isQuoteLocked(): bool
    {
        return $this->quoteClient->isQuoteLocked($this->getQuote());
    }

    /**
     * @return bool
     */
    protected function isQuoteValid(): bool
    {
        if (!$this->config->isQuoteValidationEnabled()) {
            return true;
        }

        return $this->quoteResponseTransfer->getIsSuccessfulOrFail();
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    protected function getRemoveCartItemForm(): FormView
    {
        return $this->formFactory->getRemoveForm()->createView();
    }
}
