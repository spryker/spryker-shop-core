<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Sanitizer;

use Generated\Shared\Transfer\ConfiguratorStateTransfer;

interface ConfiguratorStateSanitizerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfiguratorStateTransfer $configuratorStateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguratorStateTransfer
     */
    public function sanitizeConfiguratorStateFormData(ConfiguratorStateTransfer $configuratorStateTransfer): ConfiguratorStateTransfer;
}
