<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CompanyUserPage\Form;

class CompanyUserAccountDataProvider
{
    public function getData()
    {
        return [
        ];
    }

    public function getOptions()
    {
        return [
           CompanyUserAccountFrom::OPTION_COMPANY_USER_ACCOUNT_CHOICES => $this->getCompanyUserAccountChoices()
        ];
    }

    protected function getCompanyUserAccountChoices()
    {
        return [];
    }
}