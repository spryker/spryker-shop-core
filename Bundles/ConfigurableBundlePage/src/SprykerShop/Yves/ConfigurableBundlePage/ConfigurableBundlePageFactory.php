<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundlePageSearchClientInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleStorageClientInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Form\ConfiguratorStateForm;
use SprykerShop\Yves\ConfigurableBundlePage\Mapper\ConfiguredBundleRequestMapper;
use SprykerShop\Yves\ConfigurableBundlePage\Mapper\ConfiguredBundleRequestMapperInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Reader\ConfigurableBundleTemplateStorageReader;
use SprykerShop\Yves\ConfigurableBundlePage\Reader\ConfigurableBundleTemplateStorageReaderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class ConfigurableBundlePageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Reader\ConfigurableBundleTemplateStorageReaderInterface
     */
    public function createConfigurableBundleTemplateStorageReader(): ConfigurableBundleTemplateStorageReaderInterface
    {
        return new ConfigurableBundleTemplateStorageReader($this->getConfigurableBundleStorageClient());
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Mapper\ConfiguredBundleRequestMapperInterface
     */
    public function createConfiguredBundleRequestMapper(): ConfiguredBundleRequestMapperInterface
    {
        return new ConfiguredBundleRequestMapper();
    }

    /**
     * @param array[] $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getConfiguratorStateForm(array $data = [], array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ConfiguratorStateForm::class, $data, $options);
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundlePageSearchClientInterface
     */
    public function getConfigurableBundlePageSearchClient(): ConfigurableBundlePageToConfigurableBundlePageSearchClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlePageDependencyProvider::CLIENT_CONFIGURABLE_BUNDLE_PAGE_SEARCH);
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleStorageClientInterface
     */
    public function getConfigurableBundleStorageClient(): ConfigurableBundlePageToConfigurableBundleStorageClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlePageDependencyProvider::CLIENT_CONFIGURABLE_BUNDLE_STORAGE);
    }
}
