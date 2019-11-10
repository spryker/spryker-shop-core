<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleCartNoteWidget;

use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Dependency\Client\ConfigurableBundleCartNoteWidgetToConfigurableBundleCartNoteClientInterface;
use SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Dependency\Client\ConfigurableBundleCartNoteWidgetToGlossaryStorageClientInterface;
use SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Form\ConfigurableBundleCartNoteForm;
use SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Form\DataProvider\ConfigurableBundleCartNoteFormDataProvider;
use SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Form\DataProvider\ConfigurableBundleCartNoteFormDataProviderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class ConfigurableBundleCartNoteWidgetFactory extends AbstractFactory
{
    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer|null $configuredBundleTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getConfigurableBundleCartNoteForm(?ConfiguredBundleTransfer $configuredBundleTransfer = null): FormInterface
    {
        $configurableBundleCartNoteFormDataProvider = $this->createConfigurableBundleCartNoteFormDataProvider();

        return $this->getFormFactory()
            ->create(
                ConfigurableBundleCartNoteForm::class,
                $configurableBundleCartNoteFormDataProvider->getData($configuredBundleTransfer),
                $configurableBundleCartNoteFormDataProvider->getOptions()
            );
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Form\DataProvider\ConfigurableBundleCartNoteFormDataProviderInterface
     */
    public function createConfigurableBundleCartNoteFormDataProvider(): ConfigurableBundleCartNoteFormDataProviderInterface
    {
        return new ConfigurableBundleCartNoteFormDataProvider();
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Dependency\Client\ConfigurableBundleCartNoteWidgetToConfigurableBundleCartNoteClientInterface
     */
    public function getConfigurableBundleCartNoteClient(): ConfigurableBundleCartNoteWidgetToConfigurableBundleCartNoteClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleCartNoteWidgetDependencyProvider::CLIENT_CONFIGURABLE_BUNDLE_CART_NOTE);
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Dependency\Client\ConfigurableBundleCartNoteWidgetToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): ConfigurableBundleCartNoteWidgetToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleCartNoteWidgetDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}
