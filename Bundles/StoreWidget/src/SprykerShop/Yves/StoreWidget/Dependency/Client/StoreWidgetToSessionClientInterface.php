<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StoreWidget\Dependency\Client;

interface StoreWidgetToSessionClientInterface
{
    /**
     * @param string $name The attribute name
     * @param mixed $default The default value if not found.
     *
     * @return mixed
     */
    public function get(string $name, mixed $default = null): mixed;

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function set(string $name, mixed $value): void;
}
