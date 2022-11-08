<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use Generated\Shared\Transfer\SessionEntityResponseTransfer;

/**
 * Use this plugin interface to provide functionality to save agent's session.
 */
interface SessionAgentSaverPluginInterface
{
    /**
     * Specification:
     * - Saves agent's session data to storage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SessionEntityRequestTransfer $sessionEntityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SessionEntityResponseTransfer
     */
    public function saveSession(SessionEntityRequestTransfer $sessionEntityRequestTransfer): SessionEntityResponseTransfer;
}
