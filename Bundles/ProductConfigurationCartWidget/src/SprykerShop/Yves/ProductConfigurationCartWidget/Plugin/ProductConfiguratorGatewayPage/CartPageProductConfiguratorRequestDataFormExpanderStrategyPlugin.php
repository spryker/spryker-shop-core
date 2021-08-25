<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationCartWidget\Plugin\ProductConfiguratorGatewayPage;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorRequestDataFormExpanderStrategyPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetConfig getConfig()
 */
class CartPageProductConfiguratorRequestDataFormExpanderStrategyPlugin extends AbstractPlugin implements ProductConfiguratorRequestDataFormExpanderStrategyPluginInterface
{
    /**
     * @uses \SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\ProductConfiguratorRequestDataForm::OPTION_SOURCE_TYPE
     */
    protected const OPTION_SOURCE_TYPE = ProductConfiguratorRequestDataTransfer::SOURCE_TYPE;

    /**
     * {@inheritDoc}
     * - Checks if source type is equal to cart.
     *
     * @api
     *
     * @param array $options
     *
     * @return bool
     */
    public function isApplicable(array $options): bool
    {
        $sourceType = $options[static::OPTION_SOURCE_TYPE] ?? null;

        return $sourceType === $this->getConfig()->getCartSourceType();
    }

    /**
     * {@inheritDoc}
     * - Extends the product configurator request form with the SKU, quantity, and key group fields to support configuration for a cart item on a cart page.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        return $this->getFactory()
            ->createCartPageProductConfiguratorRequestDataFormExpander()
            ->expandProductConfiguratorRequestDataForm($builder, $options);
    }
}
