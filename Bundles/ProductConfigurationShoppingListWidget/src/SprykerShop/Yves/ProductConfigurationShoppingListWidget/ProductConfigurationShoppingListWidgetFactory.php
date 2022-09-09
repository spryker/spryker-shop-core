<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationShoppingListWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductConfigurationShoppingListWidget\Checker\ShoppingListPageApplicabilityChecker;
use SprykerShop\Yves\ProductConfigurationShoppingListWidget\Checker\ShoppingListPageApplicabilityCheckerInterface;
use SprykerShop\Yves\ProductConfigurationShoppingListWidget\Dependency\Client\ProductConfigurationShoppingListWidgetToProductConfigurationShoppingListClientInterface;
use SprykerShop\Yves\ProductConfigurationShoppingListWidget\Expander\ShoppingListPageProductConfiguratorRequestDataFormExpander;
use SprykerShop\Yves\ProductConfigurationShoppingListWidget\Expander\ShoppingListPageProductConfiguratorRequestDataFormExpanderInterface;
use SprykerShop\Yves\ProductConfigurationShoppingListWidget\Form\DataProvider\ProductConfiguratorButtonFormDataProvider;
use SprykerShop\Yves\ProductConfigurationShoppingListWidget\Form\ProductConfigurationButtonForm;
use SprykerShop\Yves\ProductConfigurationShoppingListWidget\Processor\ProductConfiguratorResponseProcessor;
use SprykerShop\Yves\ProductConfigurationShoppingListWidget\Processor\ProductConfiguratorResponseProcessorInterface;
use SprykerShop\Yves\ProductConfigurationShoppingListWidget\Resolver\ProductConfigurationTemplateResolver;
use SprykerShop\Yves\ProductConfigurationShoppingListWidget\Resolver\ProductConfigurationTemplateResolverInterface;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\ProductConfigurationShoppingListWidget\ProductConfigurationShoppingListWidgetConfig getConfig()
 */
class ProductConfigurationShoppingListWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductConfigurationShoppingListWidget\Resolver\ProductConfigurationTemplateResolverInterface
     */
    public function createProductConfigurationTemplateResolver(): ProductConfigurationTemplateResolverInterface
    {
        return new ProductConfigurationTemplateResolver(
            $this->getShoppingListItemProductConfigurationRenderStrategyPlugins(),
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
     * @return \SprykerShop\Yves\ProductConfigurationShoppingListWidget\Checker\ShoppingListPageApplicabilityCheckerInterface
     */
    public function createShoppingListPageApplicabilityChecker(): ShoppingListPageApplicabilityCheckerInterface
    {
        return new ShoppingListPageApplicabilityChecker(
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationShoppingListWidget\Form\DataProvider\ProductConfiguratorButtonFormDataProvider
     */
    public function createProductConfiguratorButtonFormDataProvider(): ProductConfiguratorButtonFormDataProvider
    {
        return new ProductConfiguratorButtonFormDataProvider($this->getConfig());
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationShoppingListWidget\Processor\ProductConfiguratorResponseProcessorInterface
     */
    public function createProductConfiguratorResponseProcessor(): ProductConfiguratorResponseProcessorInterface
    {
        return new ProductConfiguratorResponseProcessor(
            $this->getProductConfigurationShoppingListClient(),
            $this->getRouter(),
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
     * @return \SprykerShop\Yves\ProductConfigurationShoppingListWidget\Expander\ShoppingListPageProductConfiguratorRequestDataFormExpanderInterface
     */
    public function createShoppingListPageProductConfiguratorRequestDataFormExpander(): ShoppingListPageProductConfiguratorRequestDataFormExpanderInterface
    {
        return new ShoppingListPageProductConfiguratorRequestDataFormExpander(
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationShoppingListWidget\Dependency\Client\ProductConfigurationShoppingListWidgetToProductConfigurationShoppingListClientInterface
     */
    public function getProductConfigurationShoppingListClient(): ProductConfigurationShoppingListWidgetToProductConfigurationShoppingListClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationShoppingListWidgetDependencyProvider::CLIENT_PRODUCT_CONFIGURATION_SHOPPING_LIST);
    }

    /**
     * @return array<\SprykerShop\Yves\ProductConfigurationShoppingListWidgetExtension\Dependency\Plugin\ShoppingListItemProductConfigurationRenderStrategyPluginInterface>
     */
    public function getShoppingListItemProductConfigurationRenderStrategyPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationShoppingListWidgetDependencyProvider::PLUGINS_SHOPPING_LIST_ITEM_PRODUCT_CONFIGURATION_RENDER_STRATEGY);
    }

    /**
     * @return \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    public function getRouter(): ChainRouterInterface
    {
        return $this->getProvidedDependency(ProductConfigurationShoppingListWidgetDependencyProvider::SERVICE_ROUTER);
    }
}
