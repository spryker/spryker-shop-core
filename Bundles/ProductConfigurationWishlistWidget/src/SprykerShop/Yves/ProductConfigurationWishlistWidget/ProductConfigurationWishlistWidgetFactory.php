<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWishlistWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductConfigurationWishlistWidget\Checker\WishlistPageApplicabilityChecker;
use SprykerShop\Yves\ProductConfigurationWishlistWidget\Checker\WishlistPageApplicabilityCheckerInterface;
use SprykerShop\Yves\ProductConfigurationWishlistWidget\Dependency\Client\ProductConfigurationWishlistWidgetToProductConfigurationWishlistClientInterface;
use SprykerShop\Yves\ProductConfigurationWishlistWidget\Expander\WishlistPageProductConfiguratorRequestDataFormExpander;
use SprykerShop\Yves\ProductConfigurationWishlistWidget\Expander\WishlistPageProductConfiguratorRequestDataFormExpanderInterface;
use SprykerShop\Yves\ProductConfigurationWishlistWidget\Form\DataProvider\ProductConfiguratorButtonFormDataProvider;
use SprykerShop\Yves\ProductConfigurationWishlistWidget\Form\ProductConfigurationButtonForm;
use SprykerShop\Yves\ProductConfigurationWishlistWidget\Processor\ProductConfiguratorResponseProcessor;
use SprykerShop\Yves\ProductConfigurationWishlistWidget\Processor\ProductConfiguratorResponseProcessorInterface;
use SprykerShop\Yves\ProductConfigurationWishlistWidget\Resolver\ProductConfigurationTemplateResolver;
use SprykerShop\Yves\ProductConfigurationWishlistWidget\Resolver\ProductConfigurationTemplateResolverInterface;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\ProductConfigurationWishlistWidget\ProductConfigurationWishlistWidgetConfig getConfig()
 */
class ProductConfigurationWishlistWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductConfigurationWishlistWidget\Resolver\ProductConfigurationTemplateResolverInterface
     */
    public function createProductConfigurationTemplateResolver(): ProductConfigurationTemplateResolverInterface
    {
        return new ProductConfigurationTemplateResolver(
            $this->getWishlistItemProductConfigurationRenderStrategyPlugins(),
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
     * @return \SprykerShop\Yves\ProductConfigurationWishlistWidget\Checker\WishlistPageApplicabilityCheckerInterface
     */
    public function createWishlistPageApplicabilityChecker(): WishlistPageApplicabilityCheckerInterface
    {
        return new WishlistPageApplicabilityChecker(
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationWishlistWidget\Form\DataProvider\ProductConfiguratorButtonFormDataProvider
     */
    public function createProductConfiguratorButtonFormDataProvider(): ProductConfiguratorButtonFormDataProvider
    {
        return new ProductConfiguratorButtonFormDataProvider($this->getConfig());
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationWishlistWidget\Processor\ProductConfiguratorResponseProcessorInterface
     */
    public function createProductConfiguratorResponseProcessor(): ProductConfiguratorResponseProcessorInterface
    {
        return new ProductConfiguratorResponseProcessor(
            $this->getProductConfigurationWishlistClient(),
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
     * @return \SprykerShop\Yves\ProductConfigurationWishlistWidget\Expander\WishlistPageProductConfiguratorRequestDataFormExpanderInterface
     */
    public function createWishlistPageProductConfiguratorRequestDataFormExpander(): WishlistPageProductConfiguratorRequestDataFormExpanderInterface
    {
        return new WishlistPageProductConfiguratorRequestDataFormExpander(
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationWishlistWidget\Dependency\Client\ProductConfigurationWishlistWidgetToProductConfigurationWishlistClientInterface
     */
    public function getProductConfigurationWishlistClient(): ProductConfigurationWishlistWidgetToProductConfigurationWishlistClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationWishlistWidgetDependencyProvider::CLIENT_PRODUCT_CONFIGURATION_WISHLIST);
    }

    /**
     * @return array<\SprykerShop\Yves\ProductConfigurationWishlistWidgetExtension\Dependency\Plugin\WishlistItemProductConfigurationRenderStrategyPluginInterface>
     */
    public function getWishlistItemProductConfigurationRenderStrategyPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationWishlistWidgetDependencyProvider::PLUGINS_WISHLIST_ITEM_PRODUCT_CONFIGURATION_RENDER_STRATEGY);
    }

    /**
     * @return \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    public function getRouter(): ChainRouterInterface
    {
        return $this->getProvidedDependency(ProductConfigurationWishlistWidgetDependencyProvider::SERVICE_ROUTER);
    }
}
