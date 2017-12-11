<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\NewsletterWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\NewsletterWidget\Dependency\Client\NewsletterWidgetToNewsletterClientInterface;
use SprykerShop\Yves\NewsletterWidget\Dependency\Service\NewsletterWidgetToUtilValidateServiceInterface;
use SprykerShop\Yves\NewsletterWidget\Form\NewsletterSubscriptionForm;

class NewsletterWidgetFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getNewsletterSubscriptionForm()
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY)
            ->create($this->createNewsletterSubscriptionFormType());
    }

    /**
     * @return \Symfony\Component\Form\AbstractType
     */
    protected function createNewsletterSubscriptionFormType()
    {
        return new NewsletterSubscriptionForm($this->getUtilValidateService());
    }

    /**
     * @return \SprykerShop\Yves\NewsletterWidget\Dependency\Service\NewsletterWidgetToUtilValidateServiceInterface
     */
    protected function getUtilValidateService(): NewsletterWidgetToUtilValidateServiceInterface
    {
        return $this->getProvidedDependency(NewsletterWidgetDependencyProvider::SERVICE_UTIL_VALIDATE);
    }

    /**
     * @return \SprykerShop\Yves\NewsletterWidget\Dependency\Client\NewsletterWidgetToNewsletterClientInterface
     */
    public function getNewsletterClient(): NewsletterWidgetToNewsletterClientInterface
    {
        return $this->getProvidedDependency(NewsletterWidgetDependencyProvider::CLIENT_NEWSLETTER);
    }
}
