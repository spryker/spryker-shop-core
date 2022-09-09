<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationShoppingListWidget\Plugin\ProductConfiguratorGatewayPage;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorRequestDataFormExpanderStrategyPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\ProductConfigurationShoppingListWidget\ProductConfigurationShoppingListWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ProductConfigurationShoppingListWidget\ProductConfigurationShoppingListWidgetConfig getConfig()
 */
class ShoppingListPageProductConfiguratorRequestDataFormExpanderStrategyPlugin extends AbstractPlugin implements ProductConfiguratorRequestDataFormExpanderStrategyPluginInterface
{
    /**
     * @uses \SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\ProductConfiguratorRequestDataForm::OPTION_SOURCE_TYPE
     *
     * @var string
     */
    protected const OPTION_SOURCE_TYPE = ProductConfiguratorRequestDataTransfer::SOURCE_TYPE;

    /**
     * {@inheritDoc}
     * - Checks if source type is equal to shopping list.
     *
     * @api
     *
     * @param array<string, mixed> $options
     *
     * @return bool
     */
    public function isApplicable(array $options): bool
    {
        $sourceType = $options[static::OPTION_SOURCE_TYPE] ?? null;

        return $sourceType === $this->getConfig()->getShoppingListSourceType();
    }

    /**
     * {@inheritDoc}
     * - Extends the product configurator request form with the `shoppingListItemUuid` and `quantity` fields to support configuration for a shopping list item on the Shopping List page.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        return $this->getFactory()
            ->createShoppingListPageProductConfiguratorRequestDataFormExpander()
            ->expandProductConfiguratorRequestDataForm($builder, $options);
    }
}
