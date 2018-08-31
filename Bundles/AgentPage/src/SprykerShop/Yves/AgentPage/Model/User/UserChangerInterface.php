<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Model\User;

interface UserChangerInterface
{
    /**
     * Change user token and add additional roles to it
     *
     * @return void
     */
    public function change(): void;
}
