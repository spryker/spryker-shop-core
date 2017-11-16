<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\NewsletterWidget;

use SprykerShop\Yves\NewsletterWidget\Form\NewsletterSubscriptionForm;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;

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
     * @return \Spryker\Service\UtilValidate\UtilValidateServiceInterface
     */
    protected function getUtilValidateService()
    {
        return $this->getProvidedDependency(NewsletterWidgetDependencyProvider::SERVICE_UTIL_VALIDATE);
    }
}
