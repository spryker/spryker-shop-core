<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopRouter\Dependency\Client;

class ShopRouterToCollectorClientBridge implements ShopRouterToCollectorClientInterface
{
    /**
     * @var \Spryker\Client\Collector\CollectorClientInterface
     */
    protected $collectorClient;

    /**
     * @param \Spryker\Client\Collector\CollectorClientInterface $collectorClient
     */
    public function __construct($collectorClient)
    {
        $this->collectorClient = $collectorClient;
    }

    /**
     * @param string $url
     * @param string $localeName
     *
     * @return array|bool
     */
    public function matchUrl($url, $localeName)
    {
        return $this->collectorClient->matchUrl($url, $localeName);
    }
}
