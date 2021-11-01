<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Plugin\ProductConfiguratorGatewayPage;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorRequestDataFormExpanderStrategyPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageFactory getFactory()
 * @method \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageConfig getConfig()
 */
class ProductDetailPageProductConfiguratorRequestDataFormExpanderStrategyPlugin extends AbstractPlugin implements ProductConfiguratorRequestDataFormExpanderStrategyPluginInterface
{
    protected const OPTION_SOURCE_TYPE = ProductConfiguratorRequestDataTransfer::SOURCE_TYPE;

    /**
     * {@inheritDoc}
     * - Checks if source type is equal to PDP.
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

        return $sourceType === $this->getConfig()->getPdpSourceType();
    }

    /**
     * {@inheritDoc}
     * - Extends the product configurator request form with the SKU field to support configuration for a product on the PDP page.
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
            ->createProductDetailPageProductConfiguratorRequestDataFormExpander()
            ->expandProductConfiguratorRequestDataForm($builder, $options);
    }
}
