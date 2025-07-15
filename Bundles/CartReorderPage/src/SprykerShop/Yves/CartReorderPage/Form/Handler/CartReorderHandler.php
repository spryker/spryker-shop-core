<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartReorderPage\Form\Handler;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderResponseTransfer;
use SprykerShop\Yves\CartReorderPage\Dependency\Client\CartReorderPageToCartReorderClientInterface;
use SprykerShop\Yves\CartReorderPage\Dependency\Client\CartReorderPageToCustomerClientInterface;
use SprykerShop\Yves\CartReorderPage\Dependency\Client\CartReorderPageToZedRequestClientInterface;
use Symfony\Component\HttpFoundation\Request;

class CartReorderHandler implements CartReorderHandlerInterface
{
    /**
     * @uses \SprykerShop\Yves\CartReorderPage\Widget\CartReorderItemCheckboxWidget::ATTRIBUTE_NAME_SALES_ORDER_ITEM_IDS
     *
     * @var string
     */
    protected const ATTRIBUTE_NAME_SALES_ORDER_ITEM_IDS = 'sales-order-item-ids';

    /**
     * @param \SprykerShop\Yves\CartReorderPage\Dependency\Client\CartReorderPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\CartReorderPage\Dependency\Client\CartReorderPageToCartReorderClientInterface $cartReorderClient
     * @param \SprykerShop\Yves\CartReorderPage\Dependency\Client\CartReorderPageToZedRequestClientInterface $zedRequestClient
     * @param list<\SprykerShop\Yves\CartReorderPageExtension\Dependency\Plugin\CartReorderRequestExpanderPluginInterface> $cartReorderRequestExpanderPlugins
     */
    public function __construct(
        protected CartReorderPageToCustomerClientInterface $customerClient,
        protected CartReorderPageToCartReorderClientInterface $cartReorderClient,
        protected CartReorderPageToZedRequestClientInterface $zedRequestClient,
        protected array $cartReorderRequestExpanderPlugins
    ) {
    }

    /**
     * @param string $orderReference
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function reorder(string $orderReference, Request $request): CartReorderResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CustomerTransfer $customerTransfer */
        $customerTransfer = $this->customerClient->getCustomer();

        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setOrderReference($orderReference)
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setSalesOrderItemIds($request->request->all()[static::ATTRIBUTE_NAME_SALES_ORDER_ITEM_IDS] ?? null);

        $cartReorderRequestTransfer = $this->executeCartReorderRequestExpanderPlugins($cartReorderRequestTransfer, $request);
        $cartReorderResponseTransfer = $this->cartReorderClient->reorder($cartReorderRequestTransfer);

        if (!$cartReorderResponseTransfer->getErrors()->count()) {
            $this->zedRequestClient->addResponseMessagesToMessenger();
        }

        return $cartReorderResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CartReorderRequestTransfer
     */
    protected function executeCartReorderRequestExpanderPlugins(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        Request $request
    ): CartReorderRequestTransfer {
        foreach ($this->cartReorderRequestExpanderPlugins as $cartReorderRequestExpanderPlugin) {
            $cartReorderRequestTransfer = $cartReorderRequestExpanderPlugin->expand($cartReorderRequestTransfer, $request);
        }

        return $cartReorderRequestTransfer;
    }
}
