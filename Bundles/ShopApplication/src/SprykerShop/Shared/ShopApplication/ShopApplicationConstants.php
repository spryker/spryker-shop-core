<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\ShopApplication;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ShopApplicationConstants
{
    /**
     * @var string
     */
    public const ENABLE_APPLICATION_DEBUG = 'SHOP_APPLICATION:ENABLE_APPLICATION_DEBUG';

    /**
     * Specification:
     * - Defines environment name for twig.
     *
     * @api
     *
     * @var string
     */
    public const TWIG_ENVIRONMENT_NAME = 'SHOP_APPLICATION:TWIG_ENVIRONMENT_NAME';
}
