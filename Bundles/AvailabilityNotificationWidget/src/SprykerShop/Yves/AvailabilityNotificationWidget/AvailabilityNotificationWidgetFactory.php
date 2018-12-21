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
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

class AvailabilityNotificationWidgetFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getAvailabilitySubscriptionForm(): FormInterface
    {
        return $this->getFormFactory()->create(AvailabilitySubscriptionForm::class);
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
