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
     * - Defines the mode when 404 error output is extended with stacktrace.
     *
     * @api
     */
    public const ENABLE_ERROR_404_STACK_TRACE = 'ERROR_PAGE:ENABLE_ERROR_404_STACK_TRACE';
}
