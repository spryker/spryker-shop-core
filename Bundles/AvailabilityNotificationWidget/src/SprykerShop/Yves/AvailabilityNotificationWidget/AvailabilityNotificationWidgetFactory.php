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
use SprykerShop\Yves\AvailabilityNotificationWidget\Form\AvailabilityNotificationSubscriptionForm;
use SprykerShop\Yves\AvailabilityNotificationWidget\Form\AvailabilityNotificationUnsubscriptionForm;
use SprykerShop\Yves\AvailabilityNotificationWidget\Form\DataProvider\AvailabilityNotificationSubscriptionFormDataProvider;
use SprykerShop\Yves\AvailabilityNotificationWidget\Form\DataProvider\AvailabilityNotificationUnsubscriptionFormDataProvider;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

class AvailabilityNotificationWidgetFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAvailabilityNotificationSubscriptionForm(): FormInterface
    {
        return $this->getFormFactory()->create(AvailabilityNotificationSubscriptionForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAvailabilityUnsubscribeForm(): FormInterface
    {
        return $this->getFormFactory()->create(AvailabilityNotificationUnsubscriptionForm::class);
    }

    /**
     * @return \SprykerShop\Yves\AvailabilityNotificationWidget\Form\DataProvider\AvailabilityNotificationSubscriptionFormDataProvider
     */
    public function createAvailabilityNotificationSubscriptionFormDataProvider(): AvailabilityNotificationSubscriptionFormDataProvider
    {
        return new AvailabilityNotificationSubscriptionFormDataProvider($this->getCustomerClient());
    }

    /**
     * @return \SprykerShop\Yves\AvailabilityNotificationWidget\Form\DataProvider\AvailabilityNotificationUnsubscriptionFormDataProvider
     */
    public function createAvailabilityUnsubscribeFormDataProvider(): AvailabilityNotificationUnsubscriptionFormDataProvider
    {
        return new AvailabilityNotificationUnsubscriptionFormDataProvider($this->getCustomerClient());
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
