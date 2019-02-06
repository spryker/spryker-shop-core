<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\AvailabilityNotificationWidget\Dependency\Client\AvailabilityNotificationWidgetToAvailabilityNotificationClientInterface;
use SprykerShop\Yves\AvailabilityNotificationWidget\Dependency\Client\AvailabilityNotificationWidgetToCustomerClientInterface;
use SprykerShop\Yves\AvailabilityNotificationWidget\Form\AvailabilitySubscriptionForm;
use SprykerShop\Yves\AvailabilityNotificationWidget\Form\AvailabilityUnsubscriptionForm;
use SprykerShop\Yves\AvailabilityNotificationWidget\Form\DataProvider\AvailabilitySubscriptionFormDataProvider;
use SprykerShop\Yves\AvailabilityNotificationWidget\Form\DataProvider\AvailabilityUnsubscriptionFormDataProvider;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

class AvailabilityNotificationWidgetFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAvailabilitySubscriptionForm(): FormInterface
    {
        return $this->getFormFactory()->create(AvailabilitySubscriptionForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAvailabilityUnsubscribeForm(): FormInterface
    {
        return $this->getFormFactory()->create(AvailabilityUnsubscriptionForm::class);
    }

    /**
     * @return \SprykerShop\Yves\AvailabilityNotificationWidget\Form\DataProvider\AvailabilitySubscriptionFormDataProvider
     */
    public function createAvailabilitySubscriptionFormDataProvider(): AvailabilitySubscriptionFormDataProvider
    {
        return new AvailabilitySubscriptionFormDataProvider($this->getCustomerClient());
    }

    /**
     * @return \SprykerShop\Yves\AvailabilityNotificationWidget\Form\DataProvider\AvailabilityUnsubscriptionFormDataProvider
     */
    public function createAvailabilityUnsubscribeFormDataProvider(): AvailabilitySubscriptionFormDataProvider
    {
        return new AvailabilityUnsubscriptionFormDataProvider($this->getCustomerClient());
    }

    /**
     * @return \SprykerShop\Yves\AvailabilityNotificationWidget\Dependency\Client\AvailabilityNotificationWidgetToAvailabilityNotificationClientInterface
     */
    public function getAvailabilityNotificationClient(): AvailabilityNotificationWidgetToAvailabilityNotificationClientInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationWidgetDependencyProvider::CLIENT_AVAILABILITY_NOTIFICATION);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\AvailabilityNotificationWidget\Dependency\Client\AvailabilityNotificationWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): AvailabilityNotificationWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationWidgetDependencyProvider::CLIENT_CUSTOMER);
    }
}
