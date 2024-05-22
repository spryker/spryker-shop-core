<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MultiCartWidget\DataProvider\MiniCartWidgetDataProvider;
use SprykerShop\Yves\MultiCartWidget\DataProvider\MiniCartWidgetDataProviderInterface;
use SprykerShop\Yves\MultiCartWidget\Dependency\Client\MultiCartWidgetToMultiCartClientInterface;
use SprykerShop\Yves\MultiCartWidget\Dependency\Client\MultiCartWidgetToQuoteClientInterface;
use SprykerShop\Yves\MultiCartWidget\Expander\MiniCartViewExpander;
use SprykerShop\Yves\MultiCartWidget\Expander\MiniCartViewExpanderInterface;
use SprykerShop\Yves\MultiCartWidget\Form\MultiCartClearForm;
use SprykerShop\Yves\MultiCartWidget\Form\MultiCartDuplicateForm;
use SprykerShop\Yves\MultiCartWidget\Form\MultiCartSetDefaultForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Twig\Environment;

class MultiCartWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\MultiCartWidget\DataProvider\MiniCartWidgetDataProviderInterface
     */
    public function createMiniCartWidgetDataProvider(): MiniCartWidgetDataProviderInterface
    {
        return new MiniCartWidgetDataProvider($this->getMultiCartClient());
    }

    /**
     * @return \SprykerShop\Yves\MultiCartWidget\Expander\MiniCartViewExpanderInterface
     */
    public function createMiniCartViewExpander(): MiniCartViewExpanderInterface
    {
        return new MiniCartViewExpander($this->createMiniCartWidgetDataProvider(), $this->getTwigEnvironment());
    }

    /**
     * @return \SprykerShop\Yves\MultiCartWidget\Dependency\Client\MultiCartWidgetToMultiCartClientInterface
     */
    public function getMultiCartClient(): MultiCartWidgetToMultiCartClientInterface
    {
        return $this->getProvidedDependency(MultiCartWidgetDependencyProvider::CLIENT_MULTI_CART);
    }

    /**
     * @return \SprykerShop\Yves\MultiCartWidget\Dependency\Client\MultiCartWidgetToQuoteClientInterface
     */
    public function getQuoteClient(): MultiCartWidgetToQuoteClientInterface
    {
        return $this->getProvidedDependency(MultiCartWidgetDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return array
     */
    public function getViewExtendWidgetPlugins(): array
    {
        return $this->getProvidedDependency(MultiCartWidgetDependencyProvider::PLUGINS_VIEW_EXTEND);
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(MultiCartWidgetDependencyProvider::FORM_FACTORY);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMultiCartClearForm(): FormInterface
    {
        return $this->getFormFactory()->create(MultiCartClearForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMultiCartDuplicateForm(): FormInterface
    {
        return $this->getFormFactory()->create(MultiCartDuplicateForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMultiCartSetDefaultForm(): FormInterface
    {
        return $this->getFormFactory()->create(MultiCartSetDefaultForm::class);
    }

    /**
     * @return \Twig\Environment
     */
    public function getTwigEnvironment(): Environment
    {
        return $this->getProvidedDependency(MultiCartWidgetDependencyProvider::TWIG_ENVIRONMENT);
    }
}
