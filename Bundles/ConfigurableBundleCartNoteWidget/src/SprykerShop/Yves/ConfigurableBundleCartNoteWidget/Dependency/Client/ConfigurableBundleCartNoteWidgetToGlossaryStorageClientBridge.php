<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Dependency\Client;

class ConfigurableBundleCartNoteWidgetToGlossaryStorageClientBridge implements ConfigurableBundleCartNoteWidgetToGlossaryStorageClientInterface
{
    /**
     * @var \Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface
     */
    protected $glossaryClient;

    /**
     * @param \Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface $glossaryClient
     */
    public function __construct($glossaryClient)
    {
        $this->glossaryClient = $glossaryClient;
    }

    /**
     * @param string $id
     * @param string $localeName
     * @param array $parameters
     *
     * @return string
     */
    public function translate($id, $localeName, array $parameters = [])
    {
        return $this->glossaryClient->translate($id, $localeName, $parameters);
    }
}
