<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\BusinessOnBehalfWidget\Dependency\Client;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class BusinessOnBehalfWidgetToBusinessOnBehalfClientBridge implements BusinessOnBehalfWidgetToBusinessOnBehalfClientInterface
{
    /**
     * @var \Spryker\Client\BusinessOnBehalf\BusinessOnBehalfClientInterface
     */
    protected $businessOnBehalfClient;

    /**
     * @param \Spryker\Client\BusinessOnBehalf\BusinessOnBehalfClientInterface $businessOnBehalfClient
     */
    public function __construct($businessOnBehalfClient)
    {
        $this->businessOnBehalfClient = $businessOnBehalfClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function findActiveCompanyUsersByCustomerId(CustomerTransfer $customerTransfer): CompanyUserCollectionTransfer
    {
        return $this->businessOnBehalfClient->findActiveCompanyUsersByCustomerId($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function isCompanyUserChangeAllowed(CustomerTransfer $customerTransfer): bool
    {
        return $this->businessOnBehalfClient->isCompanyUserChangeAllowed($customerTransfer);
    }
}
