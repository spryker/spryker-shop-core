<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationShoppingListWidget\Expander;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use SprykerShop\Yves\ProductConfigurationShoppingListWidget\ProductConfigurationShoppingListWidgetConfig;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;

class ShoppingListPageProductConfiguratorRequestDataFormExpander implements ShoppingListPageProductConfiguratorRequestDataFormExpanderInterface
{
    /**
     * @uses \SprykerShop\Yves\ProductConfigurationShoppingListWidget\Form\ProductConfigurationButtonForm::FILED_SHOPPING_LIST_ITEM_UUID
     *
     * @var string
     */
    protected const FILED_SHOPPING_LIST_ITEM_UUID = ProductConfiguratorRequestDataTransfer::SHOPPING_LIST_ITEM_UUID;

    /**
     * @uses \SprykerShop\Yves\ProductConfigurationShoppingListWidget\Form\ProductConfigurationButtonForm::FILED_QUANTITY
     *
     * @var string
     */
    protected const FILED_QUANTITY = ProductConfiguratorRequestDataTransfer::QUANTITY;

    /**
     * @uses \SprykerShop\Yves\ProductConfigurationShoppingListWidget\Form\ProductConfigurationButtonForm::FILED_SOURCE_TYPE
     *
     * @var string
     */
    protected const FIELD_SOURCE_TYPE = ProductConfiguratorRequestDataTransfer::SOURCE_TYPE;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_UUID_NOT_BLANK_MESSAGE = 'product_configurator_gateway_page.shopping_list_item_uuid_required';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SOURCE_TYPE_NOT_BLANK_MESSAGE = 'product_configurator_gateway_page.source_type_not_blank';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_QUANTITY_NOT_BLANK_MESSAGE = 'product_configurator_gateway_page.quantity_required';

    /**
     * @var \SprykerShop\Yves\ProductConfigurationShoppingListWidget\ProductConfigurationShoppingListWidgetConfig
     */
    protected ProductConfigurationShoppingListWidgetConfig $productConfigurationShoppingListWidgetConfig;

    /**
     * @param \SprykerShop\Yves\ProductConfigurationShoppingListWidget\ProductConfigurationShoppingListWidgetConfig $productConfigurationShoppingListWidgetConfig
     */
    public function __construct(ProductConfigurationShoppingListWidgetConfig $productConfigurationShoppingListWidgetConfig)
    {
        $this->productConfigurationShoppingListWidgetConfig = $productConfigurationShoppingListWidgetConfig;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expandProductConfiguratorRequestDataForm(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $this
            ->addShoppingListItemUuidField($builder)
            ->addQuantityField($builder)
            ->addSourceTypeField($builder);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addShoppingListItemUuidField(FormBuilderInterface $builder)
    {
        $builder->add(static::FILED_SHOPPING_LIST_ITEM_UUID, HiddenType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank(['message' => static::GLOSSARY_KEY_VALIDATION_UUID_NOT_BLANK_MESSAGE]),
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
                new EqualTo(['value' => $this->productConfigurationShoppingListWidgetConfig->getShoppingListSourceType()]),
            ],
        ]);

        return $this;
    }
}
