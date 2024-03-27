<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Reader;

use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToCompanyUserClientInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Exception\MerchantRelationRequestAccessDeniedHttpException;

class CompanyUserReader implements CompanyUserReaderInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToCompanyUserClientInterface
     */
    protected MerchantRelationRequestPageToCompanyUserClientInterface $companyUserClient;

    /**
     * @param \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToCompanyUserClientInterface $companyUserClient
     */
    public function __construct(MerchantRelationRequestPageToCompanyUserClientInterface $companyUserClient)
    {
        $this->companyUserClient = $companyUserClient;
    }

    /**
     * @throws \SprykerShop\Yves\MerchantRelationRequestPage\Exception\MerchantRelationRequestAccessDeniedHttpException
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCurrentCompanyUser(): CompanyUserTransfer
    {
        $companyUserTransfer = $this->companyUserClient->findCompanyUser();
        if (!$companyUserTransfer) {
            throw new MerchantRelationRequestAccessDeniedHttpException('Only company users are allowed to access this page');
        }

        return $companyUserTransfer;
    }
}
