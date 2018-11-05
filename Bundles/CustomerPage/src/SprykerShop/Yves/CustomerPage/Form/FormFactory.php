<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CustomerPage\CustomerPageDependencyProvider;
use SprykerShop\Yves\CustomerPage\Form\DataProvider\AddressFormDataProvider;
use SprykerShop\Yves\CustomerPage\Form\DataProvider\PasswordFormDataProvider;
use SprykerShop\Yves\CustomerPage\Form\DataProvider\RegisterFormDataProvider;

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
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getRegisterForm(array $formOptions = [])
    {
        return $this->getFormFactory()->create(RegisterForm::class, null, $formOptions);
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
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getPasswordForm(array $formOptions = [])
    {
        return $this->getFormFactory()->create(PasswordForm::class, null, $formOptions);
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Form\DataProvider\PasswordFormDataProvider
     */
    public function createPasswordFormDataProvider()
    {
        return new PasswordFormDataProvider($this->getConfig());
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Form\DataProvider\RegisterFormDataProvider
     */
    public function createRegisterFormDataProvider()
    {
        return new RegisterFormDataProvider($this->getConfig());
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
}
