<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRegistrationRequestPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MerchantRegistrationRequestPage\Dependency\Client\MerchantRegistrationRequestPageToMerchantRegistrationRequestClientInterface;
use SprykerShop\Yves\MerchantRegistrationRequestPage\Dependency\Client\MerchantRegistrationRequestPageToStoreClientInterface;
use SprykerShop\Yves\MerchantRegistrationRequestPage\Form\DataProvider\MerchantRegistrationRequestPageFormDataProvider;
use SprykerShop\Yves\MerchantRegistrationRequestPage\Form\MerchantRegistrationRequestPageForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class MerchantRegistrationRequestPageFactory extends AbstractFactory
{
    public function getMerchantRegistrationRequestForm(): FormInterface
    {
        $dataProvider = $this->createMerchantRegistrationRequestPageFormDataProvider();

        return $this->getFormFactory()
            ->create(
                MerchantRegistrationRequestPageForm::class,
                $dataProvider->getData(),
                $dataProvider->getOptions(),
            );
    }

    public function createMerchantRegistrationRequestPageFormDataProvider(): MerchantRegistrationRequestPageFormDataProvider
    {
        return new MerchantRegistrationRequestPageFormDataProvider($this->getStoreClient());
    }

    public function getStoreClient(): MerchantRegistrationRequestPageToStoreClientInterface
    {
        return $this->getProvidedDependency(MerchantRegistrationRequestPageDependencyProvider::CLIENT_STORE);
    }

    public function getMerchantRegistrationRequestClient(): MerchantRegistrationRequestPageToMerchantRegistrationRequestClientInterface
    {
        return $this->getProvidedDependency(MerchantRegistrationRequestPageDependencyProvider::CLIENT_MERCHANT_REGISTRATION_REQUEST);
    }

    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(MerchantRegistrationRequestPageDependencyProvider::FORM_FACTORY);
    }
}
