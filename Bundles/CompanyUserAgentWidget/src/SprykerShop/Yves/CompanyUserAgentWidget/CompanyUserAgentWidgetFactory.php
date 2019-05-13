<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserAgentWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CompanyUserAgentWidget\Dependency\Client\CompanyUserAgentWidgetToCompanyUserAgentClientInterface;
use SprykerShop\Yves\CompanyUserAgentWidget\Validator\CompanyUserAutocompleteValidator;
use SprykerShop\Yves\CompanyUserAgentWidget\Validator\CompanyUserAutocompleteValidatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @method \SprykerShop\Yves\CompanyUserAgentWidget\CompanyUserAgentWidgetConfig getConfig()
 */
class CompanyUserAgentWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CompanyUserAgentWidget\Validator\CompanyUserAutocompleteValidatorInterface
     */
    public function createCompanyUserAutocompleteValidator(): CompanyUserAutocompleteValidatorInterface
    {
        return new CompanyUserAutocompleteValidator(
            $this->getValidator()
        );
    }

    /**
     * @return \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }

    /**
     * @return \SprykerShop\Yves\CompanyUserAgentWidget\Dependency\Client\CompanyUserAgentWidgetToCompanyUserAgentClientInterface
     */
    public function getCompanyUserAgentClient(): CompanyUserAgentWidgetToCompanyUserAgentClientInterface
    {
        return $this->getProvidedDependency(CompanyUserAgentWidgetDependencyProvider::CLIENT_COMPANY_USER_AGENT);
    }
}
