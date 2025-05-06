<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client;

class SalesOrderAmendmentWidgetToSalesOrderAmendmentClientBridge implements SalesOrderAmendmentWidgetToSalesOrderAmendmentClientInterface
{
    /**
     * @var \Spryker\Client\SalesOrderAmendment\SalesOrderAmendmentClientInterface
     */
    protected $salesOrderAmendmentClient;

    /**
     * @param \Spryker\Client\SalesOrderAmendment\SalesOrderAmendmentClientInterface $salesOrderAmendmentClient
     */
    public function __construct($salesOrderAmendmentClient)
    {
        $this->salesOrderAmendmentClient = $salesOrderAmendmentClient;
    }

    /**
     * @return void
     */
    public function cancelOrderAmendment(): void
    {
        $this->salesOrderAmendmentClient->cancelOrderAmendment();
    }
}
