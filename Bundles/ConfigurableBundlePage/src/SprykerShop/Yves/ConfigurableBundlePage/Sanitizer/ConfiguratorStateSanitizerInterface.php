<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Sanitizer;

use Generated\Shared\Transfer\ConfiguratorStateSanitizeRequestTransfer;
use Generated\Shared\Transfer\ConfiguratorStateSanitizeResponseTransfer;

interface ConfiguratorStateSanitizerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfiguratorStateSanitizeRequestTransfer $configuratorStateSanitizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguratorStateSanitizeResponseTransfer
     */
    public function sanitizeConfiguratorStateFormData(ConfiguratorStateSanitizeRequestTransfer $configuratorStateSanitizeRequestTransfer): ConfiguratorStateSanitizeResponseTransfer;
}
