<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\ErrorPage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ErrorPageConstants
{
    /**
     * Specification:
     * - Enables stack trace output.
     *
     * @api
     */
    public const ENABLE_ERROR404_STACK_TRACE = 'ERROR_PAGE:ENABLE_ERROR404_STACK_TRACE';
}
