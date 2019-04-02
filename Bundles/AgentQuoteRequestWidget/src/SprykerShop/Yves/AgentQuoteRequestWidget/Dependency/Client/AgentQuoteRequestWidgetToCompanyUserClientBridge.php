<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserQueryTransfer;

class AgentQuoteRequestWidgetToCompanyUserClientBridge implements AgentQuoteRequestWidgetToCompanyUserClientInterface
{
    /**
     * @var \Spryker\Client\CompanyUser\CompanyUserClientInterface
     */
    protected $companyUserClient;

    /**
     * @param \Spryker\Client\CompanyUser\CompanyUserClientInterface $companyUserClient
     */
    public function __construct($companyUserClient)
    {
        $this->companyUserClient = $companyUserClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserQueryTransfer $companyUserQueryTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollectionByQuery(CompanyUserQueryTransfer $companyUserQueryTransfer): CompanyUserCollectionTransfer
    {
        return $this->companyUserClient->getCompanyUserCollectionByQuery($companyUserQueryTransfer);
    }
}
