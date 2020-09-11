<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductConfigurationWidget\Dependency\Client\ProductConfigurationWidgetToProductConfigurationClientInterface;
use SprykerShop\Yves\ProductConfigurationWidget\Form\DataProvider\ProductConfiguratorButtonFormCartPageDataProvider;
use SprykerShop\Yves\ProductConfigurationWidget\Form\DataProvider\ProductConfiguratorButtonFormProductDetailPageDataProvider;
use SprykerShop\Yves\ProductConfigurationWidget\Form\ProductConfigurationButtonForm;
use SprykerShop\Yves\ProductConfigurationWidget\Resolver\ProductConfigurationTemplateResolver;
use SprykerShop\Yves\ProductConfigurationWidget\Resolver\ProductConfigurationTemplateResolverInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\ProductConfigurationWidget\ProductConfigurationWidgetConfig getConfig()
 */
class ProductConfigurationWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductConfigurationWidget\Resolver\ProductConfigurationTemplateResolverInterface
     */
    public function createProductConfigurationTemplateResolver(): ProductConfigurationTemplateResolverInterface
    {
        return new ProductConfigurationTemplateResolver(
            $this->getProductConfigurationRenderStrategyPlugins()
        );
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getProductConfigurationButtonForm(): FormInterface
    {
        return $this->getFormFactory()->createNamed(
            $this->getConfig()->getProductConfiguratorGatewayRequestFormName(),
            ProductConfigurationButtonForm::class
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationWidget\Form\DataProvider\ProductConfiguratorButtonFormCartPageDataProvider
     */
    public function createProductConfiguratorButtonFormCartPageDataProvider(): ProductConfiguratorButtonFormCartPageDataProvider
    {
        return new ProductConfiguratorButtonFormCartPageDataProvider($this->getConfig());
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationWidget\Form\DataProvider\ProductConfiguratorButtonFormProductDetailPageDataProvider
     */
    public function createProductConfiguratorButtonFormProductDetailPageDataProvider(): ProductConfiguratorButtonFormProductDetailPageDataProvider
    {
        return new ProductConfiguratorButtonFormProductDetailPageDataProvider($this->getConfig());
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationWidgetExtension\Dependency\Plugin\ProductConfigurationRenderStrategyPluginInterface[]
     */
    public function getProductConfigurationRenderStrategyPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationWidgetDependencyProvider::PLUGINS_PRODUCT_CONFIGURATION_RENDER_STRATEGY);
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationWidget\Dependency\Client\ProductConfigurationWidgetToProductConfigurationClientInterface
     */
    public function getProductConfigurationClient(): ProductConfigurationWidgetToProductConfigurationClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationWidgetDependencyProvider::CLIENT_PRODUCT_CONFIGURATION);
    }
}
