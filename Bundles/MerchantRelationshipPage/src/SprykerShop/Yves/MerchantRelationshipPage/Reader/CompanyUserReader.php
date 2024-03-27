<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationshipPage\Reader;

use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToCompanyUserClientInterface;
use SprykerShop\Yves\MerchantRelationshipPage\Exception\MerchantRelationshipAccessDeniedHttpException;

class CompanyUserReader implements CompanyUserReaderInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToCompanyUserClientInterface
     */
    protected MerchantRelationshipPageToCompanyUserClientInterface $companyUserClient;

    /**
     * @param \SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToCompanyUserClientInterface $companyUserClient
     */
    public function __construct(MerchantRelationshipPageToCompanyUserClientInterface $companyUserClient)
    {
        $this->companyUserClient = $companyUserClient;
    }

    /**
     * @throws \SprykerShop\Yves\MerchantRelationshipPage\Exception\MerchantRelationshipAccessDeniedHttpException
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCurrentCompanyUser(): CompanyUserTransfer
    {
        $companyUserTransfer = $this->companyUserClient->findCompanyUser();
        if (!$companyUserTransfer) {
            throw new MerchantRelationshipAccessDeniedHttpException('Only company users are allowed to access this page');
        }

        return $companyUserTransfer;
    }
}
