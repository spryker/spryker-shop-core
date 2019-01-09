<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Dependency\Client;

class AvailabilityNotificationWidgetToSessionClientBridge implements AvailabilityNotificationWidgetToSessionClientInterface
{
    /**
     * @var \Spryker\Client\Session\SessionClientInterface $sessionClient
     */
    protected $sessionClient;

    /**
     * @param \Spryker\Client\Session\SessionClientInterface $sessionClient
     */
    public function __construct($sessionClient)
    {
        $this->sessionClient = $sessionClient;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->sessionClient->set('availabilityNotificationEmail', $value);
    }

    /**
     * @param string $key
     * @param string|null $default
     *
     * @return mixed
     */
    public function get(string $key, ?string $default = null)
    {
        return $this->sessionClient->get($key, $default);
    }

    /**
     * @return void
     */
    public function save(): void
    {
        $this->sessionClient->save();
    }
}
