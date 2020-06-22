<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget\Plugin\CustomerPage;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\NewsletterWidget\NewsletterSubscriptionSummaryWidgetPluginInterface;
use SprykerShop\Yves\NewsletterWidget\Widget\NewsletterSubscriptionSummaryWidget;

/**
 * @deprecated Use {@link \SprykerShop\Yves\NewsletterWidget\Widget\NewsletterSubscriptionSummaryWidget} instead.
 *
 * @method \SprykerShop\Yves\NewsletterWidget\NewsletterWidgetFactory getFactory()
 */
class NewsletterSubscriptionSummaryWidgetPlugin extends AbstractWidgetPlugin implements NewsletterSubscriptionSummaryWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function initialize(CustomerTransfer $customerTransfer): void
    {
        $widget = new NewsletterSubscriptionSummaryWidget($customerTransfer);

        $this->parameters = $widget->getParameters();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return NewsletterSubscriptionSummaryWidget::getTemplate();
    }
}
