<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Twig;

use Spryker\Shared\Twig\TwigFunctionProvider;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface;

class GetUsernameTwigFunctionProvider extends TwigFunctionProvider
{
    /**
     * @var string
     */
    protected const TWIG_FUNCTION_NAME_GET_USERNAME = 'getUsername';

    /**
     * @var \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface $customerClient
     */
    public function __construct(CustomerPageToCustomerClientInterface $customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @return string
     */
    public function getFunctionName(): string
    {
        return static::TWIG_FUNCTION_NAME_GET_USERNAME;
    }

    /**
     * @return callable
     */
    public function getFunction(): callable
    {
        return function (): ?string {
            if (!$this->customerClient->isLoggedIn()) {
                return null;
            }

            return $this->customerClient->getCustomer()->getEmail();
        };
    }
}
