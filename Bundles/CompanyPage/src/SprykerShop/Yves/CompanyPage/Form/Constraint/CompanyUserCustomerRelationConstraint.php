<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form\Constraint;

use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToLocaleClientInterface;
use Symfony\Component\Validator\Constraint;

class CompanyUserCustomerRelationConstraint extends Constraint
{
    /**
     * @var string
     */
    public const OPTION_CUSTOMER_CLIENT = 'customerClient';

    /**
     * @var string
     */
    public const OPTION_GLOSSARY_STORAGE_CLIENT = 'glossaryStorageClient';

    /**
     * @var string
     */
    public const OPTION_LOCALE_CLIENT = 'localeClient';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMPANY_USER_INVALID_COMPANY = 'company_page.validation.error.company_user.unauthorized_request';

    /**
     * @uses \Spryker\Client\CompanyUser\Zed\CompanyUserStub::GLOSSARY_KEY_GLOBAL_PERMISSION_FAILED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_GLOBAL_PERMISSION_FAILED = 'global.permission.failed';

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface
     */
    protected CompanyPageToCustomerClientInterface $customerClient;

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToGlossaryStorageClientInterface
     */
    protected CompanyPageToGlossaryStorageClientInterface $glossaryStorageClient;

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToLocaleClientInterface
     */
    protected CompanyPageToLocaleClientInterface $localeClient;

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface
     */
    public function getCustomerClient(): CompanyPageToCustomerClientInterface
    {
        return $this->customerClient;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        $message = $this->glossaryStorageClient->translate(
            static::GLOSSARY_KEY_COMPANY_USER_INVALID_COMPANY,
            $this->localeClient->getCurrentLocale(),
        );

        if ($message !== static::GLOSSARY_KEY_COMPANY_USER_INVALID_COMPANY) {
            return static::GLOSSARY_KEY_COMPANY_USER_INVALID_COMPANY;
        }

        return static::GLOSSARY_KEY_GLOBAL_PERMISSION_FAILED;
    }
}
