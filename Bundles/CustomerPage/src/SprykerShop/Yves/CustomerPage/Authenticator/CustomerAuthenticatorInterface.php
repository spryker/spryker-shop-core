<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Authenticator;

use Generated\Shared\Transfer\CustomerTransfer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

interface CustomerAuthenticatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     *
     * @return void
     */
    public function authenticateCustomer(CustomerTransfer $customerTransfer, TokenInterface $token): void;
}
