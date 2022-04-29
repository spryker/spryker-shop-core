<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Dependency\Client;

class CustomerReorderWidgetToGlossaryStorageClientBridge implements CustomerReorderWidgetToGlossaryStorageClientInterface
{
    /**
     * @var \Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct($glossaryStorageClient)
    {
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param string $key
     * @param string $localeName
     * @param array<string, mixed> $parameters
     *
     * @return string
     */
    public function translate($key, $localeName, array $parameters = []): string
    {
        return $this->glossaryStorageClient->translate($key, $localeName, $parameters);
    }
}
