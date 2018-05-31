<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client;

interface CompanyUserInvitationPageToSessionClientInterface
{
    /**
     * @param string $name The attribute name
     * @param mixed $default The default value if not found
     *
     * @return mixed
     */
    public function get($name, $default = null);

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return mixed
     */
    public function set($name, $value);
}
