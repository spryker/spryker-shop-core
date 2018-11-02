<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Dependency\Client;

interface CustomerReorderWidgetToGlossaryStorageClientInterface
{
    /**
     * @param string $key
     * @param string $localeName
     * @param array $parameters
     *
     * @return string
     */
    public function translate($key, $localeName, array $parameters = []);
}
