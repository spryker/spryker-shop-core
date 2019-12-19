<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleNoteWidget;

use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ConfigurableBundleNoteWidget\Dependency\Client\ConfigurableBundleNoteWidgetToConfigurableBundleNoteClientInterface;
use SprykerShop\Yves\ConfigurableBundleNoteWidget\Dependency\Client\ConfigurableBundleNoteWidgetToGlossaryStorageClientInterface;
use SprykerShop\Yves\ConfigurableBundleNoteWidget\Dependency\Client\ConfigurableBundleNoteWidgetToQuoteClientInterface;
use SprykerShop\Yves\ConfigurableBundleNoteWidget\Form\ConfigurableBundleNoteForm;
use SprykerShop\Yves\ConfigurableBundleNoteWidget\Handler\ConfigurableBundleNoteHandler;
use SprykerShop\Yves\ConfigurableBundleNoteWidget\Handler\ConfigurableBundleNoteHandlerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class ConfigurableBundleNoteWidgetFactory extends AbstractFactory
{
    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer|null $configuredBundleTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getConfigurableBundleNoteForm(?ConfiguredBundleTransfer $configuredBundleTransfer = null): FormInterface
    {
        return $this->getFormFactory()->create(ConfigurableBundleNoteForm::class, $configuredBundleTransfer);
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundleNoteWidget\Handler\ConfigurableBundleNoteHandlerInterface
     */
    public function createConfigurableBundleNoteHandler(): ConfigurableBundleNoteHandlerInterface
    {
        return new ConfigurableBundleNoteHandler(
            $this->getConfigurableBundleNoteClient(),
            $this->getQuoteClient()
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
     * @return \SprykerShop\Yves\ConfigurableBundleNoteWidget\Dependency\Client\ConfigurableBundleNoteWidgetToConfigurableBundleNoteClientInterface
     */
    public function getConfigurableBundleNoteClient(): ConfigurableBundleNoteWidgetToConfigurableBundleNoteClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleNoteWidgetDependencyProvider::CLIENT_CONFIGURABLE_BUNDLE_NOTE);
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundleNoteWidget\Dependency\Client\ConfigurableBundleNoteWidgetToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): ConfigurableBundleNoteWidgetToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleNoteWidgetDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundleNoteWidget\Dependency\Client\ConfigurableBundleNoteWidgetToQuoteClientInterface
     */
    public function getQuoteClient(): ConfigurableBundleNoteWidgetToQuoteClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleNoteWidgetDependencyProvider::CLIENT_QUOTE);
    }
}
