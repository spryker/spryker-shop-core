<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentWidget\Dependency\Client;

use Generated\Shared\Transfer\UserTransfer;

interface AgentWidgetToAgentClientInterface
{
    /**
     * @return bool
     */
    public function isLoggedIn(): bool;

    /**
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function getAgent(): ?UserTransfer;
}
