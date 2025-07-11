<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Dependency\Client;

interface CustomerPageToSessionClientInterface
{
    /**
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function set(string $name, mixed $value): void;

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function remove(string $name);
}
