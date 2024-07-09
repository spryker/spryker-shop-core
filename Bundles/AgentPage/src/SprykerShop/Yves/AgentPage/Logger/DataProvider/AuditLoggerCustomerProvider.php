<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Logger\DataProvider;

use Generated\Shared\Transfer\CustomerTransfer;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;

class AuditLoggerCustomerProvider implements AuditLoggerCustomerProviderInterface
{
    /**
     * @uses \SprykerShop\Yves\CustomerPage\Security\Customer
     *
     * @var string
     */
    protected const CUSTOMER_CLASS_NAME = 'SprykerShop\Yves\CustomerPage\Security\Customer';

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    protected TokenStorageInterface $tokenStorage;

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function findOriginalCustomer(): ?CustomerTransfer
    {
        $token = $this->tokenStorage->getToken();

        $customerClassName = static::CUSTOMER_CLASS_NAME;
        if ($token instanceof SwitchUserToken && $token->getUser() instanceof $customerClassName) {
            /** @var \SprykerShop\Yves\CustomerPage\Security\Customer $customer */
            $customer = $token->getUser();

            return $customer->getCustomerTransfer();
        }

        return null;
    }
}
