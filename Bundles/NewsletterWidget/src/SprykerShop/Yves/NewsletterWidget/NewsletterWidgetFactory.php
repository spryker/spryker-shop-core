<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\NewsletterWidget\Dependency\Client\NewsletterWidgetToNewsletterClientInterface;
use SprykerShop\Yves\NewsletterWidget\Form\NewsletterSubscriptionForm;
use SprykerShop\Yves\NewsletterWidget\Handler\DoubleOptInSubscriptionRequestHandler;
use SprykerShop\Yves\NewsletterWidget\Handler\SubscriptionRequestHandlerInterface;

class NewsletterWidgetFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getNewsletterSubscriptionForm()
    {
        return $this->getFormFactory()->create(NewsletterSubscriptionForm::class);
    }

    /**
     * @return \SprykerShop\Yves\NewsletterWidget\Dependency\Client\NewsletterWidgetToNewsletterClientInterface
     */
    public function getNewsletterClient(): NewsletterWidgetToNewsletterClientInterface
    {
        return $this->getProvidedDependency(NewsletterWidgetDependencyProvider::CLIENT_NEWSLETTER);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory()
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\NewsletterWidget\Handler\SubscriptionRequestHandlerInterface
     */
    public function createDoubleOptInSubscriptionRequestHandler(): SubscriptionRequestHandlerInterface
    {
        return new DoubleOptInSubscriptionRequestHandler($this->getNewsletterClient());
    }
}
