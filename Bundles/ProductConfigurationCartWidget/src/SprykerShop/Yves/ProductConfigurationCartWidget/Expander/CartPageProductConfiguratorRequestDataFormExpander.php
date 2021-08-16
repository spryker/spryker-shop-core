<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationCartWidget\Expander;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetConfig;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;

class CartPageProductConfiguratorRequestDataFormExpander implements CartPageProductConfiguratorRequestDataFormExpanderInterface
{
    /**
     * @var \SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetConfig
     */
    protected $productConfigurationCartWidgetConfig;

    /**
     * @uses \SprykerShop\Yves\ProductConfigurationCartWidget\Form\ProductConfigurationButtonForm::FILED_SKU
     */
    protected const FIELD_SKU = ProductConfiguratorRequestDataTransfer::SKU;

    /**
     * @uses \SprykerShop\Yves\ProductConfigurationCartWidget\Form\ProductConfigurationButtonForm::FILED_QUANTITY
     */
    protected const FILED_QUANTITY = ProductConfiguratorRequestDataTransfer::QUANTITY;

    /**
     * @uses \SprykerShop\Yves\ProductConfigurationCartWidget\Form\ProductConfigurationButtonForm::FILED_ITEM_GROUP_KEY
     */
    protected const FILED_ITEM_GROUP_KEY = ProductConfiguratorRequestDataTransfer::ITEM_GROUP_KEY;

    /**
     * @uses \SprykerShop\Yves\ProductConfigurationCartWidget\Form\ProductConfigurationButtonForm::FILED_SOURCE_TYPE
     */
    protected const FIELD_SOURCE_TYPE = ProductConfiguratorRequestDataTransfer::SOURCE_TYPE;

    protected const GLOSSARY_KEY_VALIDATION_SKU_NOT_BLANK_MESSAGE = 'product_configurator_gateway_page.sku_not_blank';
    protected const GLOSSARY_KEY_VALIDATION_GROUP_KEY_NOT_BLANK_MESSAGE = 'product_configurator_gateway_page.item_group_key_required';
    protected const GLOSSARY_KEY_VALIDATION_QUANTITY_NOT_BLANK_MESSAGE = 'product_configurator_gateway_page.quantity_required';
    protected const GLOSSARY_KEY_VALIDATION_SOURCE_TYPE_NOT_BLANK_MESSAGE = 'product_configurator_gateway_page.source_type_not_blank';

    /**
     * @param \SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetConfig $productConfigurationCartWidgetConfig
     */
    public function __construct(ProductConfigurationCartWidgetConfig $productConfigurationCartWidgetConfig)
    {
        $this->productConfigurationCartWidgetConfig = $productConfigurationCartWidgetConfig;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expandProductConfiguratorRequestDataForm(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $this->addSkuField($builder)
            ->addQuantityField($builder)
            ->addItemGroupKeyField($builder)
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
    protected function addItemGroupKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FILED_ITEM_GROUP_KEY, HiddenType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank(['message' => static::GLOSSARY_KEY_VALIDATION_GROUP_KEY_NOT_BLANK_MESSAGE]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addQuantityField(FormBuilderInterface $builder)
    {
        $builder->add(static::FILED_QUANTITY, HiddenType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank(['message' => static::GLOSSARY_KEY_VALIDATION_QUANTITY_NOT_BLANK_MESSAGE]),
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
                new EqualTo(['value' => $this->productConfigurationCartWidgetConfig->getCartSourceType()]),
            ],
        ]);

        return $this;
    }
}
