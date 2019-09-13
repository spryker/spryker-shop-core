<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidgetExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface HideBreadcrumbItemPluginInterface
{
    public const STATUS_DECLINED = 'declined';
    public function isBreadcrumbItemHidden(QuoteTransfer $quoteTransfer): bool;
}
