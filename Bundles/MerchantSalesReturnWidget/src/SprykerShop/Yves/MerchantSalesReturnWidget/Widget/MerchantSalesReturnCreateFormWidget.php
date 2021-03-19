<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSalesReturnWidget\Widget;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormView;

/**
 * @method \SprykerShop\Yves\MerchantSalesOrderWidget\MerchantSalesOrderWidgetFactory getFactory()
 */
class MerchantSalesReturnCreateFormWidget extends AbstractWidget
{
    /**
     * @param \Symfony\Component\Form\FormView $createReturnForm
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     */
    public function __construct(FormView $createReturnForm, OrderTransfer $orderTransfer)
    {
        $this->addCreateReturnFormParameter($createReturnForm);
        $this->addOrderParameter($orderTransfer);
        $this->addMerchantOrderReferencesParameter($orderTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'MerchantSalesReturnCreateFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MerchantSalesReturnWidget/views/merchant-sales-return-create-form-widget/merchant-sales-return-create-form-widget.twig';
    }

    /**
     * @param \Symfony\Component\Form\FormView $createReturnForm
     *
     * @return void
     */
    protected function addCreateReturnFormParameter(FormView $createReturnForm): void
    {
        $this->addParameter('createReturnForm', $createReturnForm);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function addMerchantOrderReferencesParameter(OrderTransfer $orderTransfer): void
    {
        $merchantOrderReferences = $this->extractMerchantOrderReferences($orderTransfer);

        $this->addParameter('merchantOrderReferences', $merchantOrderReferences);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function addOrderParameter(OrderTransfer $orderTransfer): void
    {
        $this->addParameter('order', $orderTransfer);
    }

    /**
     * @phpstan-return array<int, string|null>
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string[]
     */
    protected function extractMerchantOrderReferences(OrderTransfer $orderTransfer): array
    {
        $merchantOrderReferences = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $merchantOrderReference = $itemTransfer->getMerchantOrderReference();

            if (!in_array($merchantOrderReference, $merchantOrderReferences)) {
                $merchantOrderReferences[] = $merchantOrderReference;
            }
        }

        return $merchantOrderReferences;
    }
}
