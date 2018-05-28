<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CompanyUserPage\Form\DataProvider;

use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CompanyUserPage\Dependency\CompanyPageToBusinessOnBehalfClientInterface;
use SprykerShop\Yves\CompanyUserPage\Form\CompanyUserAccountFrom;

class CompanyUserAccountDataProvider
{
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
        return array_flip($this->businessOnBehalfClient->findActiveCompanyUsersByCustomerId($customerTransfer));
    }
}