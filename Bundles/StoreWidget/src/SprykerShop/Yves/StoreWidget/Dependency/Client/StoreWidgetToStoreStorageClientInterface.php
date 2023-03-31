<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StoreWidget\Dependency\Client;

interface StoreWidgetToStoreStorageClientInterface
{
    /**
     * @return array<string>
     */
    public function getStoreNames(): array;
}
