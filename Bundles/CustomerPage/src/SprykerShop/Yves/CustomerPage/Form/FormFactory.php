<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form;

use Generated\Shared\Transfer\OrderSearchFormTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CustomerPage\CustomerPageDependencyProvider;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\CustomerPage\Form\DataProvider\AddressFormDataProvider;
use SprykerShop\Yves\CustomerPage\Form\DataProvider\OrderSearchFormDataProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 */
class FormFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory()
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getAddressForm(array $formOptions = [])
    {
        return $this->getFormFactory()->create(AddressForm::class, null, $formOptions);
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Form\DataProvider\AddressFormDataProvider
     */
    public function createAddressFormDataProvider()
    {
        return new AddressFormDataProvider($this->getCustomerClient(), $this->getStore());
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getRegisterForm()
    {
        return $this->getFormFactory()->create(RegisterForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getLoginForm()
    {
        return $this->getFormFactory()->create(LoginForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getForgottenPasswordForm()
    {
        return $this->getFormFactory()->create(ForgottenPasswordForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getProfileForm()
    {
        return $this->getFormFactory()->create(ProfileForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getFormRestorePassword()
    {
        return $this->getFormFactory()->create(RestorePasswordForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getPasswordForm()
    {
        return $this->getFormFactory()->create(PasswordForm::class);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderSearchFormTransfer|null $orderSearchFormTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getOrderSearchForm(?OrderSearchFormTransfer $orderSearchFormTransfer = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(OrderSearchForm::class, $orderSearchFormTransfer, $options);
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Form\DataProvider\OrderSearchFormDataProvider
     */
    public function createOrderSearchFormDataProvider(): OrderSearchFormDataProvider
    {
        return new OrderSearchFormDataProvider(
            $this->getConfig(),
            $this->getGlossaryStorageClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::STORE);
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): CustomerPageToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\OrderSearchFormFormExpanderPluginInterface[]
     */
    public function getOrderSearchFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::PLUGINS_ORDER_SEARCH_FORM_EXPANDER);
    }
}
