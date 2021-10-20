<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationCartWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductConfigurationCartWidget\Checker\CartPageApplicabilityChecker;
use SprykerShop\Yves\ProductConfigurationCartWidget\Checker\CartPageApplicabilityCheckerInterface;
use SprykerShop\Yves\ProductConfigurationCartWidget\Dependency\Client\ProductConfigurationCartWidgetToProductConfigurationCartClientInterface;
use SprykerShop\Yves\ProductConfigurationCartWidget\Expander\CartPageProductConfiguratorRequestDataFormExpander;
use SprykerShop\Yves\ProductConfigurationCartWidget\Expander\CartPageProductConfiguratorRequestDataFormExpanderInterface;
use SprykerShop\Yves\ProductConfigurationCartWidget\Form\DataProvider\ProductConfigurationButtonFormDataProvider;
use SprykerShop\Yves\ProductConfigurationCartWidget\Form\ProductConfigurationButtonForm;
use SprykerShop\Yves\ProductConfigurationCartWidget\Processor\ProductConfiguratorResponseProcessor;
use SprykerShop\Yves\ProductConfigurationCartWidget\Processor\ProductConfiguratorResponseProcessorInterface;
use SprykerShop\Yves\ProductConfigurationCartWidget\Resolver\ProductConfigurationTemplateResolver;
use SprykerShop\Yves\ProductConfigurationCartWidget\Resolver\ProductConfigurationTemplateResolverInterface;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetConfig getConfig()
 */
class ProductConfigurationCartWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductConfigurationCartWidget\Resolver\ProductConfigurationTemplateResolverInterface
     */
    public function createProductConfigurationTemplateResolver(): ProductConfigurationTemplateResolverInterface
    {
        return new ProductConfigurationTemplateResolver(
            $this->getCartProductConfigurationRenderStrategyPlugins(),
        );
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getProductConfigurationButtonForm(): FormInterface
    {
        return $this->getFormFactory()->createNamed(
            $this->getConfig()->getProductConfiguratorGatewayRequestFormName(),
            ProductConfigurationButtonForm::class,
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationCartWidget\Form\DataProvider\ProductConfigurationButtonFormDataProvider
     */
    public function createProductConfigurationButtonFormDataProvider(): ProductConfigurationButtonFormDataProvider
    {
        return new ProductConfigurationButtonFormDataProvider($this->getConfig());
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationCartWidget\Expander\CartPageProductConfiguratorRequestDataFormExpanderInterface
     */
    public function createCartPageProductConfiguratorRequestDataFormExpander(): CartPageProductConfiguratorRequestDataFormExpanderInterface
    {
        return new CartPageProductConfiguratorRequestDataFormExpander($this->getConfig());
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationCartWidget\Processor\ProductConfiguratorResponseProcessorInterface
     */
    public function createProductConfiguratorResponseProcessor(): ProductConfiguratorResponseProcessorInterface
    {
        return new ProductConfiguratorResponseProcessor(
            $this->getProductConfigurationCartClient(),
            $this->getRouter(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationCartWidget\Checker\CartPageApplicabilityCheckerInterface
     */
    public function createCartPageApplicabilityChecker(): CartPageApplicabilityCheckerInterface
    {
        return new CartPageApplicabilityChecker(
            $this->getConfig(),
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
     * @return \SprykerShop\Yves\ProductConfigurationCartWidget\Dependency\Client\ProductConfigurationCartWidgetToProductConfigurationCartClientInterface
     */
    public function getProductConfigurationCartClient(): ProductConfigurationCartWidgetToProductConfigurationCartClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationCartWidgetDependencyProvider::CLIENT_PRODUCT_CONFIGURATION_CART);
    }

    /**
     * @return array<\SprykerShop\Yves\ProductConfigurationCartWidgetExtension\Dependency\Plugin\CartProductConfigurationRenderStrategyPluginInterface>
     */
    public function getCartProductConfigurationRenderStrategyPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationCartWidgetDependencyProvider::PLUGINS_CART_PRODUCT_CONFIGURATION_RENDER_STRATEGY);
    }

    /**
     * @return \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    public function getRouter(): ChainRouterInterface
    {
        return $this->getProvidedDependency(ProductConfigurationCartWidgetDependencyProvider::SERVICE_ROUTER);
    }
}
