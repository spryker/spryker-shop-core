<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use Generated\Shared\Transfer\SessionEntityResponseTransfer;

/**
 * Use this plugin interface to provide functionality to validate agent's session.
 */
interface SessionAgentValidatorPluginInterface
{
    /**
     * Specification:
     * - Validates agent's session data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SessionEntityRequestTransfer $sessionEntityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SessionEntityResponseTransfer
     */
    public function validate(SessionEntityRequestTransfer $sessionEntityRequestTransfer): SessionEntityResponseTransfer;
}
