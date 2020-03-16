<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\FilterFieldCheckRequestTransfer;
use Generated\Shared\Transfer\FilterFieldCheckResponseTransfer;

class CompanyBusinessUnitWidgetToCompanyBusinessUnitSalesConnectorClientBridge implements CompanyBusinessUnitWidgetToCompanyBusinessUnitSalesConnectorClientInterface
{
    /**
     * @var \Spryker\Client\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorClientInterface
     */
    protected $companyBusinessUnitSalesConnectorClient;

    /**
     * @param \Spryker\Client\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorClientInterface $companyBusinessUnitSalesConnectorClient
     */
    public function __construct($companyBusinessUnitSalesConnectorClient)
    {
        $this->companyBusinessUnitSalesConnectorClient = $companyBusinessUnitSalesConnectorClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function getPermittedCompanyBusinessUnitCollection(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyBusinessUnitCollectionTransfer {
        return $this->companyBusinessUnitSalesConnectorClient->getPermittedCompanyBusinessUnitCollection($companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldCheckRequestTransfer $filterFieldCheckRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FilterFieldCheckResponseTransfer
     */
    public function isCompanyRelatedFiltersSet(
        FilterFieldCheckRequestTransfer $filterFieldCheckRequestTransfer
    ): FilterFieldCheckResponseTransfer {
        return $this->companyBusinessUnitSalesConnectorClient->isCompanyRelatedFiltersSet($filterFieldCheckRequestTransfer);
    }
}
