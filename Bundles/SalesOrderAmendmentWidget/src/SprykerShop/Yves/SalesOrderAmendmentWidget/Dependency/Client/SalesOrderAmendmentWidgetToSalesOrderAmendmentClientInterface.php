<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client;

interface SalesOrderAmendmentWidgetToSalesOrderAmendmentClientInterface
{
    /**
     * @return void
     */
    public function cancelOrderAmendment(): void;
}
