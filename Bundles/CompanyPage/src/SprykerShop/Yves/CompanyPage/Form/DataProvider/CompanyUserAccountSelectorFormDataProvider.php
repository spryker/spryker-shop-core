<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form\DataProvider;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToBusinessOnBehalfClientInterface;
use SprykerShop\Yves\CompanyPage\Form\CompanyUserAccountSelectorForm;

class CompanyUserAccountSelectorFormDataProvider
{
    /**
     * @var string
     */
    protected const FORMAT_COMPANY_USER_DISPLAY = '%s / %s';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_NO_COMPANY = 'company_user.no_company';

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToBusinessOnBehalfClientInterface
     */
    protected $businessOnBehalfClient;

    /**
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToBusinessOnBehalfClientInterface $businessOnBehalfClient
     */
    public function __construct(CompanyPageToBusinessOnBehalfClientInterface $businessOnBehalfClient)
    {
        $this->businessOnBehalfClient = $businessOnBehalfClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array
     */
    public function getData(CustomerTransfer $customerTransfer): array
    {
        return [
            CompanyUserAccountSelectorForm::FIELD_COMPANY_USER_ACCOUNT_CHOICE => $customerTransfer->getCompanyUserTransfer()
                ? $customerTransfer->getCompanyUserTransfer()->getIdCompanyUser()
                : null,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyCollection
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array
     */
    public function getOptions(CompanyUserCollectionTransfer $companyCollection, CustomerTransfer $customerTransfer): array
    {
        return [
            CompanyUserAccountSelectorForm::OPTION_COMPANY_USER_ACCOUNT_CHOICES => $this->mapCompanyUserCollectionToChoiceArray($companyCollection),
            CompanyUserAccountSelectorForm::OPTION_COMPANY_USER_ACCOUNT_DEFAULT_SELECTED => $this->isDefaultCompanyUserSelected($customerTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyCollection
     *
     * @return array<int>
     */
    protected function mapCompanyUserCollectionToChoiceArray(CompanyUserCollectionTransfer $companyCollection): array
    {
        $companies = [];
        foreach ($companyCollection->getCompanyUsers() as $companyUser) {
            $key = sprintf(
                static::FORMAT_COMPANY_USER_DISPLAY,
                $companyUser->getCompany()->getName(),
                $companyUser->getCompanyBusinessUnit()->getName(),
            );

            $companies[$key] = $companyUser->getIdCompanyUser();
        }

        return [static::GLOSSARY_KEY_NO_COMPANY => ''] + $companies;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isDefaultCompanyUserSelected(CustomerTransfer $customerTransfer): bool
    {
        return $customerTransfer->getCompanyUserTransfer()
            && $customerTransfer->getCompanyUserTransfer()->getIsDefault();
    }
}
