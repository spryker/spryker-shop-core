<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin\Provider;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Application\Plugin\Provider\YvesControllerProvider as SprykerYvesControllerProvider;

/**
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
abstract class AbstractYvesControllerProvider extends SprykerYvesControllerProvider
{
    /**
     * @return string
     */
    public function getAllowedLocalesPattern()
    {
        $systemLocales = Store::getInstance()->getLocales();
        $implodedLocales = implode('|', array_keys($systemLocales));
        $allowedLocalesPattern = '(' . $implodedLocales . ')\/';

        return $allowedLocalesPattern;
    }
}
