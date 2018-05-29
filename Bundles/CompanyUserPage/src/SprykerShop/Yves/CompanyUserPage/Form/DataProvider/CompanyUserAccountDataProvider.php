<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CompanyUserPage\Form\DataProvider;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CompanyUserPage\Dependency\CompanyPageToBusinessOnBehalfClientInterface;
use SprykerShop\Yves\CompanyUserPage\Form\CompanyUserAccountFrom;

class CompanyUserAccountDataProvider
{
    protected const FORMAT_COMPANY_USER_DISPLAY = '%s / %s';

    /**
     * @var CompanyPageToBusinessOnBehalfClientInterface
     */
    protected $businessOnBehalfClient;

    /**
     * @param CompanyPageToBusinessOnBehalfClientInterface $businessOnBehalfClient
     */
    public function __construct(CompanyPageToBusinessOnBehalfClientInterface $businessOnBehalfClient)
    {
        $this->businessOnBehalfClient = $businessOnBehalfClient;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
        ];
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
           CompanyUserAccountFrom::OPTION_COMPANY_USER_ACCOUNT_CHOICES => $this->getCompanyUserAccountChoices()
        ];
    }

    /**
     * @return array
     */
    protected function getCompanyUserAccountChoices(): array
    {
        //TODO: get customer
        $customerTransfer = (new CustomerTransfer())->setIdCustomer(6);
        $companyCollection = $this->businessOnBehalfClient->findActiveCompanyUsersByCustomerId($customerTransfer);

        return $this->mapCompanyUserCollectionToChoiceArray($companyCollection);
//        return array_flip();
    }

    protected function mapCompanyUserCollectionToChoiceArray(CompanyUserCollectionTransfer $companyCollection): array
    {
        $companies = [];
        foreach ($companyCollection->getCompanyUsers() as $companyUser)
        {
            $companies[
                sprintf(
                    static::FORMAT_COMPANY_USER_DISPLAY,
                    $companyUser->getCompany()->getName(),
                    $companyUser->getCompanyBusinessUnit()->getName()
                )
            ] = $companyUser->getIdCompanyUser();
        }

        return $companies;
    }
}