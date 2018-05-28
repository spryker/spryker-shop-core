<?php

namespace SprykerShop\Yves\CompanyUserPage\Dependency;

use Generated\Shared\Transfer\CustomerTransfer;

interface CompanyPageToBusinessOnBehalfClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    public function findActiveCompanyUsersByCustomerId(CustomerTransfer $customerTransfer): array;
}