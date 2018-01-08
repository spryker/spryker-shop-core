<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Controller;

use Spryker\Shared\Storage\StorageConstants;
use Spryker\Yves\Kernel\Controller\AbstractController as SprykerAbstractController;
use Spryker\Yves\Storage\Controller\StorageCacheControllerTrait;

/**
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
abstract class AbstractController extends SprykerAbstractController
{
    use StorageCacheControllerTrait;

    const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_REPLACE;

    /**
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->initializeStorageCacheStrategy(static::STORAGE_CACHE_STRATEGY);
    }
}
