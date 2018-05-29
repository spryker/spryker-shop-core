<?php

namespace SprykerShop\Yves\CompanyUserPage\Dependency;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class CompanyPageToBusinessOnBehalfClientBridge implements CompanyPageToBusinessOnBehalfClientInterface
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
}