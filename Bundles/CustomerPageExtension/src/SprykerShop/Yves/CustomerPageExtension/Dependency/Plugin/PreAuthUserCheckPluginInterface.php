<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Use this plugin interface for checking user before authentication.
 */
interface PreAuthUserCheckPluginInterface
{
    /**
     * Specification:
     * - Checks user before authentication.
     * - Throws account status exception if the check is not passed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccountStatusException
     *
     * @return void
     */
    public function checkPreAuth(CustomerTransfer $customerTransfer, UserInterface $user): void;
}
