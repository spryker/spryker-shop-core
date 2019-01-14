<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Dependency\Client;

interface AvailabilityNotificationWidgetToSessionClientInterface
{
    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function set(string $key, $value): void;

    /**
     * @param string $key
     * @param string|null $default
     *
     * @return mixed
     */
    public function get(string $key, ?string $default = null);

    /**
     * @return void
     */
    public function save(): void;
}
