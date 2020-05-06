<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Extractor;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Service\QuoteRequestAgentPageToShipmentServiceInterface;

class ExpenseExtractor implements ExpenseExtractorInterface
{
    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     */
    public const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Service\QuoteRequestAgentPageToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Service\QuoteRequestAgentPageToShipmentServiceInterface $shipmentService
     */
    public function __construct(QuoteRequestAgentPageToShipmentServiceInterface $shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer[]
     */
    public function extractShipmentExpenses(QuoteTransfer $quoteTransfer): array
    {
        $shipmentExpenses = [];

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() !== static::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $shipmentHashKey = $this->shipmentService->getShipmentHashKey($expenseTransfer->getShipment());
            $shipmentExpenses[$shipmentHashKey] = $expenseTransfer;
        }

        return $shipmentExpenses;
    }
}
