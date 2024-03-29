<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Dependency\Plugin\NewsletterWidget;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Use {@link \SprykerShop\Yves\NewsletterWidget\Widget\NewsletterSubscriptionSummaryWidget} instead.
 */
interface NewsletterSubscriptionSummaryWidgetPluginInterface extends WidgetPluginInterface
{
    /**
     * @var string
     */
    public const NAME = 'NewsletterSubscriptionSummaryWidgetPlugin';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function initialize(CustomerTransfer $customerTransfer): void;
}
