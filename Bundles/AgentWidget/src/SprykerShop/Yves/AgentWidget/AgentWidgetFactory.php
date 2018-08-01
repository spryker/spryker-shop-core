<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\AgentWidget\Dependency\Client\AgentWidgetToAgentClientInterface;
use SprykerShop\Yves\AgentWidget\Validator\CustomerAutocompleteValidator;
use SprykerShop\Yves\AgentWidget\Validator\CustomerAutocompleteValidatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AgentWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\AgentWidget\Dependency\Client\AgentWidgetToAgentClientInterface
     */
    public function getAgentClient(): AgentWidgetToAgentClientInterface
    {
        return $this->getProvidedDependency(AgentWidgetDependencyProvider::CLIENT_AGENT);
    }

    /**
     * @return \SprykerShop\Yves\AgentWidget\Validator\CustomerAutocompleteValidatorInterface
     */
    public function createCustomerAutocompleteValidator(): CustomerAutocompleteValidatorInterface
    {
        return new CustomerAutocompleteValidator(
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
}
