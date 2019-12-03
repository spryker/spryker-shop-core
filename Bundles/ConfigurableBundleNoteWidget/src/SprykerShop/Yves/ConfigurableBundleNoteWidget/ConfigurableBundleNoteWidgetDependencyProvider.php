<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleNoteWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ConfigurableBundleNoteWidget\Dependency\Client\ConfigurableBundleNoteWidgetToConfigurableBundleNoteClientBridge;
use SprykerShop\Yves\ConfigurableBundleNoteWidget\Dependency\Client\ConfigurableBundleNoteWidgetToGlossaryStorageClientBridge;
use SprykerShop\Yves\ConfigurableBundleNoteWidget\Dependency\Client\ConfigurableBundleNoteWidgetToQuoteClientBridge;

class ConfigurableBundleNoteWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CONFIGURABLE_BUNDLE_NOTE = 'CLIENT_CONFIGURABLE_BUNDLE_NOTE';
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addConfigurableBundleNoteClient($container);
        $container = $this->addGlossaryStorageClient($container);
        $container = $this->addQuoteClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addConfigurableBundleNoteClient(Container $container): Container
    {
        $container->set(static::CLIENT_CONFIGURABLE_BUNDLE_NOTE, function (Container $container) {
            return new ConfigurableBundleNoteWidgetToConfigurableBundleNoteClientBridge(
                $container->getLocator()->configurableBundleNote()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_GLOSSARY_STORAGE, function (Container $container) {
            return new ConfigurableBundleNoteWidgetToGlossaryStorageClientBridge(
                $container->getLocator()->glossaryStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteClient(Container $container): Container
    {
        $container->set(static::CLIENT_QUOTE, function (Container $container) {
            return new ConfigurableBundleNoteWidgetToQuoteClientBridge(
                $container->getLocator()->quote()->client()
            );
        });

        return $container;
    }
}
