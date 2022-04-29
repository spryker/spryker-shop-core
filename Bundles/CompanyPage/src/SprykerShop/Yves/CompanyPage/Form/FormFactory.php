<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CompanyPage\CompanyPageDependencyProvider;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToBusinessOnBehalfClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToLocaleClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToStoreClientInterface;
use SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyBusinessUnitFormDataProvider;
use SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyRoleDataProvider;
use SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyRolePermissionDataProvider;
use SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyUnitAddressFormDataProvider;
use SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyUserAccountSelectorFormDataProvider;
use SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyUserFormDataProvider;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyRegisterForm(): FormInterface
    {
        return $this->getFormFactory()->create(CompanyRegisterForm::class);
    }

    /**
     * @param array<string, mixed> $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getBusinessUnitForm(array $formOptions): FormInterface
    {
        return $this->getFormFactory()->create(CompanyBusinessUnitForm::class, null, $formOptions);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyBusinessUnitDeleteForm(): FormInterface
    {
        return $this->getFormFactory()->create(CompanyBusinessUnitDeleteForm::class);
    }

    /**
     * @param array<string, mixed> $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyUnitAddressForm(array $formOptions): FormInterface
    {
        return $this->getFormFactory()->create(CompanyUnitAddressForm::class, null, $formOptions);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyUnitAddressDeleteForm(): FormInterface
    {
        return $this->getFormFactory()->create(CompanyUnitAddressDeleteForm::class);
    }

    /**
     * @param array<string, mixed> $formOptions
     * @param array|null $formData
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyBusinessUnitAddressForm(array $formOptions, ?array $formData = null): FormInterface
    {
        return $this->getFormFactory()->create(CompanyBusinessUnitAddressForm::class, $formData, $formOptions);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyRoleForm(): FormInterface
    {
        return $this->getFormFactory()->create(CompanyRoleForm::class);
    }

    /**
     * @param array<string, mixed> $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyUserForm(array $formOptions): FormInterface
    {
        return $this->getFormFactory()->create(CompanyUserForm::class, null, $formOptions);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyUserDeleteForm(CompanyUserTransfer $data, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(CompanyUserDeleteForm::class, $data, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyRoleDeleteForm(CompanyRoleTransfer $data, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(CompanyRoleDeleteForm::class, $data, $options);
    }

    /**
     * @SuppressWarnings(PHPMD)
     *
     * @return \SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyUserFormDataProvider
     */
    public function createCompanyUserFormDataProvider(): CompanyUserFormDataProvider
    {
        return new CompanyUserFormDataProvider(
            $this->getCompanyUserClient(),
            $this->getCompanyBusinessUnitClient(),
            $this->getCompanyRoleClient(),
        );
    }

    /**
     * @param int $idCompanyRole
     * @param int $idPermission
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyRolePermissionType(int $idCompanyRole, int $idPermission)
    {
        $dataProvider = $this->createCompanyRolePermissionDataProvider();

        return $this->getFormFactory()->create(
            CompanyRolePermissionConfigurationType::class,
            $dataProvider->getData($idCompanyRole, $idPermission),
            $dataProvider->getOptions(),
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyRolePermissionDataProvider
     */
    public function createCompanyRolePermissionDataProvider()
    {
        return new CompanyRolePermissionDataProvider(
            $this->getCompanyRoleClient(),
        );
    }

    /**
     * @SuppressWarnings(PHPMD)
     *
     * @return \SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyBusinessUnitFormDataProvider
     */
    public function createBusinessUnitFormDataProvider(): CompanyBusinessUnitFormDataProvider
    {
        return new CompanyBusinessUnitFormDataProvider(
            $this->getCompanyBusinessUnitClient(),
            $this->getCompanyUnitAddressClient(),
            $this->getGlossaryStorageClient(),
            $this->getLocaleClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface
     */
    public function getCompanyBusinessUnitClient(): CompanyPageToCompanyBusinessUnitClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @SuppressWarnings(PHPMD)
     *
     * @return \SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyUnitAddressFormDataProvider
     */
    public function createCompanyUnitAddressFormDataProvider(): CompanyUnitAddressFormDataProvider
    {
        return new CompanyUnitAddressFormDataProvider(
            $this->getCompanyUnitAddressClient(),
            $this->getCompanyBusinessUnitClient(),
            $this->getStoreClient(),
        );
    }

    /**
     * @SuppressWarnings(PHPMD)
     *
     * @return \SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyRoleDataProvider
     */
    public function createCompanyRoleDataProvider(): CompanyRoleDataProvider
    {
        return new CompanyRoleDataProvider($this->getCompanyRoleClient());
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyUserAccountForm(array $data = [], array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(CompanyUserAccountSelectorForm::class, $data, $formOptions);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyUserAccountSelectorFormDataProvider
     */
    public function createCompanyUserAccountDataProvider(): CompanyUserAccountSelectorFormDataProvider
    {
        return new CompanyUserAccountSelectorFormDataProvider(
            $this->getBusinessOnBehalfClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToBusinessOnBehalfClientInterface
     */
    public function getBusinessOnBehalfClient(): CompanyPageToBusinessOnBehalfClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_BUSINESS_ON_BEHALF);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface
     */
    public function getCompanyUnitAddressClient(): CompanyPageToCompanyUnitAddressClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_COMPANY_UNIT_ADDRESS);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientInterface
     */
    public function getCompanyRoleClient(): CompanyPageToCompanyRoleClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_COMPANY_ROLE);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientInterface
     */
    public function getCompanyUserClient(): CompanyPageToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): CompanyPageToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToStoreClientInterface
     */
    public function getStoreClient(): CompanyPageToStoreClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToLocaleClientInterface
     */
    public function getLocaleClient(): CompanyPageToLocaleClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_LOCALE);
    }
}
