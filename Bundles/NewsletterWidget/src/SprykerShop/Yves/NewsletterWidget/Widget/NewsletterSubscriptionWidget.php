<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormView;

/**
 * @method \SprykerShop\Yves\NewsletterWidget\NewsletterWidgetFactory getFactory()
 */
class NewsletterSubscriptionWidget extends AbstractWidget
{
    public function __construct()
    {
        $this->addParameter('newsletterSubscriptionForm', $this->getNewsletterSubscriptionForm());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'NewsletterSubscriptionWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@NewsletterWidget/views/subscription-widget-form/subscription-widget-form.twig';
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    protected function getNewsletterSubscriptionForm(): FormView
    {
        $subscriptionForm = $this
            ->getFactory()
            ->getNewsletterSubscriptionForm();

        return $subscriptionForm->createView();
    }
}
