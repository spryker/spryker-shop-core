<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget\Plugin\CustomerPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\NewsletterWidget\NewsletterSubscriptionSummaryWidgetPluginInterface;

class NewsletterSubscriptionSummaryWidgetPlugin extends AbstractWidgetPlugin implements NewsletterSubscriptionSummaryWidgetPluginInterface
{

    /**
     * @param bool $isSubscribed
     *
     * @return void
     */
    public function initialize($isSubscribed): void
    {
        $this->addParameter('isSubscribed',
            $isSubscribed
        );
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
        return '@NewsletterWidget/_customer-page/newsletter-subscription-summary.twig';
    }
}
