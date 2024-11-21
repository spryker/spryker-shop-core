<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\TraceableEventWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class TraceableEventWidgetConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Shared\KernelApp\KernelAppConstants::TENANT_IDENTIFIER
     *
     * @api
     *
     * @return string
     */
    public function getTenantIdentifier(): string
    {
        return $this->get('KERNEL_APP:TENANT_IDENTIFIER');
    }

    /**
     * @uses \SprykerShop\Shared\ShopApplication\ShopApplicationConstants::ENABLE_APPLICATION_DEBUG
     *
     * @api
     *
     * @return bool
     */
    public function isDebugEnabled(): bool
    {
        return $this->get('SHOP_APPLICATION:ENABLE_APPLICATION_DEBUG', false);
    }
}
