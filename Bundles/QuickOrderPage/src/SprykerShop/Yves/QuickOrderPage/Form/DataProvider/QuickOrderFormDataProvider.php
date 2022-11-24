<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToLocaleClientInterface;
use SprykerShop\Yves\QuickOrderPage\Form\QuickOrderForm;
use SprykerShop\Yves\QuickOrderPage\Form\QuickOrderItemEmbeddedForm;
use SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig;

class QuickOrderFormDataProvider implements QuickOrderFormDataProviderInterface
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig
     */
    protected $config;

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig $config
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToLocaleClientInterface $localeClient
     */
    public function __construct(QuickOrderPageConfig $config, QuickOrderPageToLocaleClientInterface $localeClient)
    {
        $this->config = $config;
        $this->localeClient = $localeClient;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            QuickOrderForm::OPTION_LOCALE => $this->localeClient->getCurrentLocale(),
        ];
    }

    /**
     * @param array<\Generated\Shared\Transfer\QuickOrderItemTransfer> $orderItems
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function getQuickOrderTransfer(array $orderItems = []): QuickOrderTransfer
    {
        $quickOrderTransfer = (new QuickOrderTransfer())
            ->setItems(new ArrayObject($orderItems));

        if (count($orderItems) > 0) {
            return $quickOrderTransfer;
        }

        $this->appendEmptyQuickOrderItems($quickOrderTransfer);

        return $quickOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrder
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function appendEmptyQuickOrderItems(QuickOrderTransfer $quickOrder): QuickOrderTransfer
    {
        $itemsNumber = $this->config->getDefaultDisplayedRowCount();

        $quickOrderItemCollection = $quickOrder->getItems();
        for ($i = 0; $i < $itemsNumber; $i++) {
            $quickOrderItemCollection->append(new QuickOrderItemTransfer());
        }

        return $quickOrder;
    }

    /**
     * @param array<array<string, mixed>> $formDataItems
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function mapFormDataToQuickOrderItems(array $formDataItems): QuickOrderTransfer
    {
        $quickOrderItems = [];
        foreach ($formDataItems as $formDataItem) {
            if (empty($formDataItem[QuickOrderItemEmbeddedForm::FIELD_SKU]) && !isset($formDataItem[QuickOrderItemEmbeddedForm::FIELD_QUANTITY])) {
                continue;
            }

            $quickOrderItems[] = (new QuickOrderItemTransfer())
                ->fromArray($formDataItem, true)
                ->setQuantity(isset($formDataItem[QuickOrderItemEmbeddedForm::FIELD_QUANTITY]) ? (int)$formDataItem[QuickOrderItemEmbeddedForm::FIELD_QUANTITY] : null);
        }

        return $this->getQuickOrderTransfer($quickOrderItems);
    }
}
