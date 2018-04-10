<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\SharedCartPage\Form\DataProvider\ShareCartFormDataProvider;
use SprykerShop\Yves\SharedCartPage\Form\DataProvider\ShareCartFormDataProviderInterface;
use SprykerShop\Yves\SharedCartPage\Form\ShareCartForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class SharedCartPageFactory extends AbstractFactory
{
    /**
     * @param int $idQuote
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getShareCartForm($idQuote): FormInterface
    {
        $dataProvider = $this->createShareCartFormDataProvider();

        return $this->getFormFactory()->create(
            ShareCartForm::class,
            $dataProvider->getData($idQuote),
            $dataProvider->getOptions()
        );
    }

    /**
     * @return \SprykerShop\Yves\SharedCartPage\Form\DataProvider\ShareCartFormDataProviderInterface
     */
    public function createShareCartFormDataProvider(): ShareCartFormDataProviderInterface
    {
        return new ShareCartFormDataProvider(
            $this->getCustomerClient(),
            $this->getCompanyUserClient(),
            $this->getSharedCartClient()
        );
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToCustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(SharedCartPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToCompanyUserClientInterface
     */
    public function getCompanyUserClient()
    {
        return $this->getProvidedDependency(SharedCartPageDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToSharedCartClientInterface
     */
    public function getSharedCartClient()
    {
        return $this->getProvidedDependency(SharedCartPageDependencyProvider::CLIENT_SHARED_CART);
    }
}
