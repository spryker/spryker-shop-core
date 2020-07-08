<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Security;

use Generated\Shared\Transfer\CustomerTransfer;
use Symfony\Component\Security\Core\User\UserInterface;

interface CustomerUserInterface extends UserInterface
{
    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransfer(): CustomerTransfer;
}
