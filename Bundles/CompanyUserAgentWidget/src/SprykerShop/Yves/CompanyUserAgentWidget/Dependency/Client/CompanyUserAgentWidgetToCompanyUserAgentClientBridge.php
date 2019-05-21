<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserAgentWidget\Dependency\Client;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaTransfer;

class CompanyUserAgentWidgetToCompanyUserAgentClientBridge implements CompanyUserAgentWidgetToCompanyUserAgentClientInterface
{
    /**
     * @var \Spryker\Client\CompanyUserAgent\CompanyUserAgentClientInterface
     */
    protected $companyUserAgentClient;

    /**
     * @param \Spryker\Client\CompanyUserAgent\CompanyUserAgentClientInterface $companyUserAgentClient
     */
    public function __construct($companyUserAgentClient)
    {
        $this->companyUserAgentClient = $companyUserAgentClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaTransfer $companyUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollectionByCriteria(CompanyUserCriteriaTransfer $companyUserCriteriaTransfer): CompanyUserCollectionTransfer
    {
        return $this->companyUserAgentClient->getCompanyUserCollectionByCriteria($companyUserCriteriaTransfer);
    }
}
