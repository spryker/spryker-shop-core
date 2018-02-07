<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterPage;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\NewsletterPage\Dependency\Client\NewsletterPageToCustomerClientInterface;
use SprykerShop\Yves\NewsletterPage\Dependency\Client\NewsletterPageToNewsletterClientInterface;
use SprykerShop\Yves\NewsletterPage\Form\DataProvider\NewsletterSubscriptionFormDataProvider;
use SprykerShop\Yves\NewsletterPage\Form\NewsletterSubscriptionForm;

class NewsletterPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\NewsletterPage\Form\DataProvider\NewsletterSubscriptionFormDataProvider
     */
    public function createNewsletterSubscriptionFormDataProvider()
    {
        return new NewsletterSubscriptionFormDataProvider($this->getNewsletterClient());
    }

    /**
     * @param array|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createNewsletterSubscriptionForm(array $data = null, array $options = [])
    {
        return $this->getFormFactory()->create(NewsletterSubscriptionForm::class, $data, $options);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    protected function getFormFactory()
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\NewsletterPage\Dependency\Client\NewsletterPageToNewsletterClientInterface
     */
    public function getNewsletterClient(): NewsletterPageToNewsletterClientInterface
    {
        return $this->getProvidedDependency(NewsletterPageDependencyProvider::CLIENT_NEWSLETTER);
    }

    /**
     * @return \SprykerShop\Yves\NewsletterPage\Dependency\Client\NewsletterPageToCustomerClientInterface
     */
    public function getCustomerClient(): NewsletterPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(NewsletterPageDependencyProvider::CLIENT_CUSTOMER);
    }
}
