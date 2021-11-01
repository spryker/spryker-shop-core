<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Expander;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageConfig;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductDetailPageProductConfiguratorRequestDataFormExpander implements ProductDetailPageProductConfiguratorRequestDataFormExpanderInterface
{
    /**
     * @var \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageConfig
     */
    protected $productConfiguratorGatewayPageConfig;

    protected const FIELD_SKU = ProductConfiguratorRequestDataTransfer::SKU;
    protected const FIELD_SOURCE_TYPE = ProductConfiguratorRequestDataTransfer::SOURCE_TYPE;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SKU_NOT_BLANK_MESSAGE = 'product_configurator_gateway_page.sku_not_blank';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SOURCE_TYPE_NOT_BLANK_MESSAGE = 'product_configurator_gateway_page.source_type_not_blank';

    /**
     * @param \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageConfig $productConfiguratorGatewayPageConfig
     */
    public function __construct(ProductConfiguratorGatewayPageConfig $productConfiguratorGatewayPageConfig)
    {
        $this->productConfiguratorGatewayPageConfig = $productConfiguratorGatewayPageConfig;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expandProductConfiguratorRequestDataForm(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $this->addSkuField($builder)
            ->addSourceTypeField($builder);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SKU, HiddenType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank(['message' => static::GLOSSARY_KEY_VALIDATION_SKU_NOT_BLANK_MESSAGE]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSourceTypeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SOURCE_TYPE, HiddenType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank(['message' => static::GLOSSARY_KEY_VALIDATION_SOURCE_TYPE_NOT_BLANK_MESSAGE]),
                new EqualTo(['value' => $this->productConfiguratorGatewayPageConfig->getPdpSourceType()]),
            ],
        ]);

        return $this;
    }
}
